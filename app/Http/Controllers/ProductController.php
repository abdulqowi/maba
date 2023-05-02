<?php

namespace App\Http\Controllers;

use Exception;
use App\product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\Collection;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::all();
        foreach ($products as $product) {
            $product->image = asset('assets/images/' . $product->image);
            $categories = $product->categories()->pluck('name');
            $product->categories = $categories;
        }

        return apiResponse('200', 'Product', 'success', $products);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $rules = [
                'name' => 'required',
                'price' => 'required',
                'quantity' => 'required',
                'desc' => 'required',
                'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'categories' => 'required',
                'categories.*' => 'integer|exists:product_categories,id'

            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return apiResponse(400, 'error', 'Data tidak lengkap ', $validator->errors());
            }

            $product = Product::create([
                'name' => $request->name,
                'price' => $request->price,
                'quantity' => $request->quantity,
                'desc' => $request->desc,
                'image' => $request->image
            ]);
            $product->categories()->sync($request->categories);


            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $name = time() . '.' . $image->getClientOriginalExtension();
                $destinationPath = public_path('/assets/images');
                $image->move($destinationPath, $name);
                $product->image = $name;
            }

            $product->save();
            $product->image = asset('assets/images/' . $product->image);
            $categories = $product->categories()->pluck('name');
            $product->categories = $categories;
            return apiResponse('200', 'Product', 'Created :', $product);
        } catch (Exception $e) {
            return apiResponse('400', 'error', 'error', $e);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\product  $product
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $product = Product::find($id);
            if ($product) {
                $product->image = asset('assets/images/' . $product->image);
                $categories = $product->categories()->pluck('name');
                $product->categories = $categories;
                return apiResponse('200', 'Product', 'Detail :', $product);
            } else {
                return apiResponse('404', 'error', 'Product not found', null);
            }
        } catch (Exception $e) {
            return apiResponse('400', 'error', 'error', $e);
        }
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\product  $product
     * @return \Illuminate\Http\Response
     */
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $product = Product::find($id);

            if (!$product) {
                return apiResponse(404, 'error', 'Produk tidak ditemukan');
            }

            $rules = [
                'name' => 'sometimes|required',
                'price' => 'sometimes|required',
                'quantity' => 'sometimes|required',
                'desc' => 'sometimes|required',
                'categories' => 'sometimes|required',
                'image' => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return apiResponse(400, 'error', 'Data tidak lengkap ', $validator->errors());
            }

            if ($request->hasFile('image')) {
                // hapus gambar lama jika ada
                if ($product->image && file_exists(public_path('assets/images/' . $product->image))) {
                    unlink(public_path('assets/images/' . $product->image));
                }

                $image = $request->file('image');
                $name = time() . '.' . $image->getClientOriginalExtension();
                $destinationPath = public_path('/assets/images');
                $image->move($destinationPath, $name);
                $product->image = $name;
            }
            // Mendapatkan id kategori yang diinginkan dari request
            $category_id = $request->input('category_id');
            $product->categories()->detach();
            $product->categories()->attach($category_id);

            $product->name = $request->get('name', $product->name);
            $product->price = $request->get('price', $product->price);
            $product->quantity = $request->get('quantity', $product->quantity);
            $product->desc = $request->get('desc', $product->desc);
            $product->save();

            $product->image = asset('assets/images/' . $product->image);
            $categories = $product->categories()->pluck('name');
            $product->categories = $categories;
            return apiResponse(200, 'Product', 'Updated :', $product);
        } catch (Exception $e) {
            return apiResponse(400, 'error', 'error', $e);
        }
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $product = Product::findOrFail($id);
            $imagePath = public_path('/assets/images/') . $product->image;
            if (file_exists($imagePath) && $product->image) {
                unlink($imagePath);
            }
            $product->delete();
            return apiResponse('200', 'Product', 'Deleted', null);
        } catch (Exception $e) {
            return apiResponse('400', 'error', 'error', $e);
        }
    }
}

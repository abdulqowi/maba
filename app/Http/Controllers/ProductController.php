<?php

namespace App\Http\Controllers;

use Exception;
use App\product;
use Illuminate\Http\Request;
use App\Product as AppProduct;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

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
        if ($product->image) {
            $product->image = asset('assets/images/' . $product->image);
        }
    }
    return apiResponse('200', 'Product', 'Listing :', $products);
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
                'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
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

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $name = time() . '.' . $image->getClientOriginalExtension();
                $destinationPath = public_path('/assets/images');
                $image->move($destinationPath, $name);
                $product->image = $name;
            }

            $product->save();
            $product->image = asset('assets/images/' . $product->image);
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
    public function show(product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(product $product)
    {
        //
    }

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
    
            $product->name = $request->get('name', $product->name);
            $product->price = $request->get('price', $product->price);
            $product->quantity = $request->get('quantity', $product->quantity);
            $product->desc = $request->get('desc', $product->desc);
    
            $product->save();
    
            $product->image = asset('assets/images/' . $product->image);
    
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

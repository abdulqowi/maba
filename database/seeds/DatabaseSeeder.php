<?php

use App\Product;
use App\Receipt;
use Illuminate\Database\Seeder;
use Receipt as GlobalReceipt;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            UserSeeder::class,
            CategorySeeder::class,
            // EducationSeeder::class,
            // NotificationSeeder::class,
            // MasterSeeder::class,
            ProductSeeder::class,
            MediaSeeder::class,
            // CourseSeeder::class,
            CategoryProductSeeder::class,
        ]);
    }
}

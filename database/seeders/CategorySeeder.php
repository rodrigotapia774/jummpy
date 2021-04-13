<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Category::create([
            'user_id' => 1,
            'parent_id' => 0,
            'name' => 'Tecnología',
            'state' => 1,
        ]);

        Category::create([
            'user_id' => 1,
            'parent_id' => 0,
            'name' => 'Construcción',
            'state' => 1,
        ]);

        Category::create([
            'user_id' => 1,
            'parent_id' => 0,
            'name' => 'Muebles',
            'state' => 1,
        ]);

        Category::create([
            'user_id' => 1,
            'parent_id' => 0,
            'name' => 'Transporte',
            'state' => 1,
        ]);
    }
}

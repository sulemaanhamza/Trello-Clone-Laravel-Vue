<?php

namespace Database\Seeders;

use App\Models\Card;
use App\Models\Category;
use Illuminate\Database\Seeder;

class CategoryCardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Category::factory()
                ->count(6)
                ->hasCards(rand(6,14))
                ->create();
    }
}

<?php

namespace App\Repositories;

use App\Models\Category;
use Exception;

class CategoryRepository
{
    public function getCategories($filters=[])
    {
        $query = Category::query()->with('cards');

        if(count($filters) > 0)
        {
            $query->where($filters);
        }

        $categories = $query->get();


        return $categories;
    }



    public function getSingleCategory($id)
    {
        return Category::find($id);
    }



    public function storeCategory($title)
    {
        if(!empty($title))
        {
            $category = Category::firstOrCreate(['title'=>$title]);

            return $category;
        }


        throw new Exception('Title is required to store new category');
    }
}

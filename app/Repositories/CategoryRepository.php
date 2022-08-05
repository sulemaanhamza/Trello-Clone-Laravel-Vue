<?php

namespace App\Repositories;

use App\Models\Category;
use Exception;

class CategoryRepository
{
    public function getCategories($filters=[])
    {
        $query = Category::query()->with('cards',function($query) {
            return $query->whereStatus(true);
        });

        if(count($filters) > 0)
        {
            $query->where($filters);
        }

        $categories = $query->where('status',true)->get();


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


    public function disableLinkedCards($category)
    {
        if($category->cards()->update(['status'=>false]))
        {
            return true;
        }

        return false;
    }

    public function disable($category)
    {
        if($category->update(['status'=>false]))
        {
            return true;
        }

        return false;
    }
}

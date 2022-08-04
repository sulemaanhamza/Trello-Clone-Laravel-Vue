<?php

namespace App\Repositories;

use App\Models\Card;
use App\Models\Category;
use Exception;

class CardRepository
{

    public function getCards($filters=[])
    {
        $query = Card::query();

        if(count($filters) > 0)
        {
            $query->where($filters);
        }

        $cards = $query->get();


        return $cards;
    }

    public function getSingleCard($id)
    {
        return Card::find($id);
    }


    public function storeNewCard($category_id, $title, $description = null)
    {
        if(!empty($category) && !empty($title))
        {
            $category = Category::find($category_id);

            if($category)
            {
                $card = $category->cards()->create([
                    'title' =>  $title,
                    'description' => $description
                ]);


                return $card;
            }
            else
            {
                throw new Exception('Category not found');
            }
        }

        throw new Exception('Category_id or title not found');
    }
}

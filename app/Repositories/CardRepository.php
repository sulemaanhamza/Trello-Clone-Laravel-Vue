<?php

namespace App\Repositories;

use App\Models\Card;
use App\Models\Category;
use Carbon\Carbon;
use Exception;
use Throwable;

class CardRepository
{

    public function getCards($filters=[])
    {
        $query = Card::query();

        if(count($filters) > 0)
        {
            if(!empty($filters['date']))
            {
                try
                {
                    $filterDate = Carbon::createFromFormat('Y-m-d', $filters['date']);

                    if($filterDate)
                    {
                        $query->whereDate('created_at',$filterDate);
                    }
                }
                catch(Throwable $e)
                {
                    report($e);
                }
            }

            if(isset($filters['status']))
            {
                $filterStatus = (bool) $filters['status'];

                $query->whereStatus($filterStatus);
            }
        }

        $cards = $query->latest()->get();


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

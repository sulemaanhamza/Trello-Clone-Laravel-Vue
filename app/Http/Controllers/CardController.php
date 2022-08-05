<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\Category;
use Illuminate\Http\Request;

class CardController extends Controller
{
    public function store(Request $request)
    {
        if($request->has(['category_id','title']))
        {
            if($category = Category::whereStatus(true)->whereId($request->input('category_id'))->first())
            {
               $card = $category->cards()->create([
                   'title'  =>  $request->input('title'),
                   'description' => $request->input('description') ?? null
               ]);

               if($card)
               {
                    return response()->json(['status'=>'success']);
               }
            }
        }

        return response()->json(['status'=>'error'],500);
    }

    public function update(Card $card, Request $request)
    {
        if($request->has('category'))
        {
            if(Category::whereStatus(true)->whereId($request->input('category'))->exists())
            {
                if($card->update(['category_id'=>$request->input('category')]))
                {
                    return response()->json(['status'=>'success']);
                }
            }
        }


        return response()->json(['status'=>'error'],500);
    }
}

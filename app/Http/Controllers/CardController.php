<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\Category;
use App\Repositories\CardRepository;
use Illuminate\Http\Request;

class CardController extends Controller
{
    protected $rep;

    public function __construct()
    {
        $this->repo = new CardRepository;
    }

    public function index(Request $request)
    {
        $cards = $this->repo->getCards($request->except('access_token'));

        return response()->json($cards);
    }

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
        $request->validate(['title'=>'required']);

        $updated = $card->update([
                            'title'         =>  $request->input('title'),
                            'description'   =>  $request->input('description') ?? null
        ]);

        if($updated)
        {
            return response()->json(['status'=>'success']);
        }

        return response()->json(['status'=>'error'],500);
    }

    public function updateCategory(Card $card, Request $request)
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

    public function destroy(Card $card)
    {
        if($card->update(['status'=>false]))
        {
            return response()->json(['status'=>'success']);
        }

        return response()->json(['status'=>'error'],500);
    }
}

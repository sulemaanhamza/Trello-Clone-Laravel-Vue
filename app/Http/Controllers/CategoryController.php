<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Repositories\CategoryRepository;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    protected $repo;

    public function __construct()
    {
        $this->repo = app(CategoryRepository::class);
    }


    public function index()
    {
        $categories = $this->repo->getCategories();

        return response()->json(['categories'=>$categories]);
    }


    public function store(Request $request)
    {
        $request->validate([
            'title' =>  'required|unique:categories,title'
        ]);


        $category = Category::create([
            'title' =>  $request->input('title')
        ]);

        if($category)
        {
            return response()->json(['status'=>'success']);
        }


        return response()->json(['status'=>'error'],500);

    }


    public function destroy(Category $category)
    {
        $this->repo->disableLinkedCards($category); // If Any

        if($this->repo->disable($category))
        {
            return response()->json(['status'=>'success']);
        }
        return response()->json(['status'=>'error'],500);
    }
}

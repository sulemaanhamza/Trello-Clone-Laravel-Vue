<?php

namespace App\Http\Controllers;

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
}

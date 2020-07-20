<?php

namespace App\Http\Controllers;

use App\Model\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    protected $_model; 
    public function __construct(Category $_model) {
        $this->_model = $_model;
    }
    public function get(Request $request){
       $categories = $this->_model
        ->queryLevel((int)$request->input('level'))
        ->orderBy('created_at', 'desc')
        ->paginate((int) $request->input('limit') ?? 10);

        return response()->json([
            'categories' => $categories
        ], 200);
    }

}

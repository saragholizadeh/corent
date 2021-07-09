<?php

namespace App\Http\Controllers\Api\Stack\Main;

use App\Http\Controllers\Controller;
use App\Models\StackCategory;
use Illuminate\Http\Request;

class StackQuestionCategoryController extends Controller
{
    public function show($id){

        $categoryQuestions = StackCategory::with('questions')->find($id);

        return response()->json([
            $categoryQuestions
        ]);
    }
}

<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CaytegoryController extends Controller
{
    public function category_list(Request $request)
    {
        try {
            $type = $request->input('type');
            $perpage = $request->perpage ?? 10;

            $query = Category::query();

            if ($type == 'main') {
                $query->where('parent_id', null);
            }

            $categories = $query->paginate($perpage);

            return response()->json($categories, 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'An error occurred while fetching data: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function category_list_under($id)
    {
        try {
            $categories = Category::where('parent_id', $id)->paginate(10);
            return response()->json($categories, 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'An error occurred while fetching data: ' . $e->getMessage(),
            ], 500);
        }
    }
}

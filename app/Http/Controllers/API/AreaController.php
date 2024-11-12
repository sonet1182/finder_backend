<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\District;
use App\Models\Division;
use App\Models\Thana;
use Illuminate\Http\Request;

class AreaController extends Controller
{
    public function division_list()
    {
        try {
            $tests = Division::all();
            return response()->json($tests, 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'An error occurred while fetching Divisions: ' . $e->getMessage(),
            ], 500);
        }
    }
    public function district_list($id)
    {
        try {
            $tests = District::where('division_id', $id)->get();
            return response()->json($tests, 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'An error occurred while fetching Districts: ' . $e->getMessage(),
            ], 500);
        }
    }
    public function thana_list($id)
    {
        try {
            $tests = Thana::where('district_id', $id)->get();
            return response()->json($tests, 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'An error occurred while fetching thana: ' . $e->getMessage(),
            ], 500);
        }
    }
}

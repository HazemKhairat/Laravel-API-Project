<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Traits\ApiHandler;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    use ApiHandler;
    public function create(Request $request)
    {
        $rules = [
            'name_ar' => 'required|string',
            'name_en' => 'required|string',
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($validator, $code);
        }
        $category = Category::create([
            'name_ar' => $request->name_ar,
            'name_en' => $request->name_en,
        ]);

        if ($category) {
            return $this->returnSuccessMessage("Category Has been Added Successfully", 200 );

        }
        return $this->returnError("SomeThing Went Wrong !", 200);
    }

    public function getAll(Request $request)
    {
        $categories = Category::select("id", "name_".app()->getLocale())->get();
        if ($categories) {
            return $this->returnData("Categories",$categories, "Data Retrived successfully");
        }
        return response()->json(["message" => "Not Found"]);
    }

    public function update(Request $request)
    {
        try {
            $rules = [
                'name_ar' => 'required|string',
                'name_en' => 'required|string',
            ];
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($validator, $code);
            }
            $category = Category::find($request->id);
            if($category){
                $category->name_ar = $request->name_ar;
                $category->name_en =  $request->name_en;
                $category->save();
                return response()->json(["message" => "Category Updated Successfully"]);
            }
            else{
                return response()->json(["message" => "Category not found"]);
            }
            
        } catch (\Exception $e) {
            return response()->json(["message" => $e->getMessage()]);
        }

    }

    public function delete(Request $request)
    {
        try {
            $category = Category::find($request->id);
            if($category){
                $category->delete();
                return response()->json(["message" => "Category Deleted Successfully"]);
            }
            return response()->json(["message" => "Category isn't exist"]);
        }
        catch(\Exception $e){
            return response()->json(["Some thing Wrong"]);
        }
    }

    public function getById(Request $request){
        $category = Category::find($request->id);
        if($category){
            return response()->json(["message" => $category]);
        }
        return response()->json(["message" => "Category not found"]);

    }
}

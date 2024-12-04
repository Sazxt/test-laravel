<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoriesController extends Controller
{
    //
    public function index()
    {
        $catgeories = Categories::all();
        return view("categories",compact("catgeories"));
    }

    public function save(Request $request)
    {
        $request->validate([
            'categoriname' => 'required|string|max:255',
        ]);

        Categories::create([
            'name' => $request->input('categoriname'),
        ]);

        // Redirect dengan pesan sukses
        return redirect()->back()->with('success', 'Category successfully created.');
    }

    public function detail($id)
    {
        try {
            $catgeories = Categories::findOrFail($id);

            return response()->json([
                'id' => $catgeories->id,
                'name' => $catgeories->name
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Catgories not found'
            ], 404);
        }
    }

    public function update(Request $request)
    {
        try {
            // Validation rules
            $validator = Validator::make($request->all(), [
                'id' => 'required|exists:categories,id',
                'categoriname' => [
                    'required',
                    'string',
                    'min:2',
                    'max:255',
                    function($attribute, $value, $fail) use ($request) {
                        $existingAuthor = Categories::where('name', $value)
                            ->where('id', '!=', $request->input('id'))
                            ->first();
                        
                        if ($existingAuthor) {
                            $fail('The categori name is already taken.');
                        }
                    }
                ]
            ], [
                'categoriname.required' => 'categori name is required.',
                'categoriname.min' => 'categori name must be at least 2 characters.',
                'categoriname.max' => 'categori name cannot exceed 255 characters.',
                'id.exists' => 'Invalid categori selected.'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'errors' => $validator->errors()->toArray()
                ], 422);
            }

            $catgeories = Categories::findOrFail($request->input('id'));
            $catgeories->name = $request->input('categoriname');
            $catgeories->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Categori updated successfully',
                'data' => $catgeories
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An unexpected error occurred',
                'error_details' => $e->getMessage()
            ], 500);
        }
    }

    public function delete($id)
    {

        $catgeories = Categories::find($id);


        if (!$catgeories) {
            return redirect()->back()->with('error', 'cat$catgeories not found.');
        }


        $catgeories->delete();


        return redirect()->back()->with('success', "Categories '{$catgeories->name}' has been deleted successfully.");
    }
}

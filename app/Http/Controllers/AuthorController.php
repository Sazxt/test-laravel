<?php

namespace App\Http\Controllers;

use App\Models\Authors;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthorController extends Controller
{
    //

    public function index()
    {
        $authors = Authors::all();
        return view("author", compact("authors"));
    }

    public function save(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Authors::create([
            'name' => $request->input('name'),
        ]);

        return redirect()->back()->with('success', 'Authors created successfully.');
    }

    public function delete($id)
    {

        $author = Authors::find($id);
        if (!$author) {
            return redirect()->back()->with('error', 'Author not found.');
        }
        $author->delete();
        return redirect()->back()->with('success', "Author '{$author->name}' has been deleted successfully.");
    }

    public function update(Request $request)
    {
        try {
            // Validation rules
            $validator = Validator::make($request->all(), [
                'id' => 'required|exists:authors,id',
                'name' => [
                    'required',
                    'string',
                    'min:2',
                    'max:255',
                    function($attribute, $value, $fail) use ($request) {
                        $existingAuthor = Authors::where('name', $value)
                            ->where('id', '!=', $request->input('id'))
                            ->first();
                        
                        if ($existingAuthor) {
                            $fail('The author name is already taken.');
                        }
                    }
                ]
            ], [
                'name.required' => 'Author name is required.',
                'name.min' => 'Author name must be at least 2 characters.',
                'name.max' => 'Author name cannot exceed 255 characters.',
                'id.exists' => 'Invalid author selected.'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'errors' => $validator->errors()->toArray()
                ], 422);
            }

            $author = Authors::findOrFail($request->input('id'));
            $author->name = $request->input('name');
            $author->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Author updated successfully',
                'data' => $author
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An unexpected error occurred',
                'error_details' => $e->getMessage()
            ], 500);
        }
    }

    public function detail($id)
    {
        try {
            $author = Authors::findOrFail($id);

            return response()->json([
                'id' => $author->id,
                'name' => $author->name
            ]);
        } catch (\Exception $e) {
            // Handle any errors
            return response()->json([
                'error' => 'Author not found'
            ], 404);
        }
    }

}

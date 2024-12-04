<?php

namespace App\Http\Controllers;

use App\Models\Books;
use Illuminate\Http\Request;
use App\Models\Authors;
use App\Models\Categories;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class BookController extends Controller
{
    //
    public function index(Request $request)
    {
        $sortField = $request->query('sort_by', 'title');
        $sortOrder = $request->query('order', 'asc'); 

        $validFields = ['title', 'author_name', 'category_name', 'publication_date'];
        if (!in_array($sortField, $validFields)) {
            $sortField = 'title';
        }
    
        $books = Books::query()
            ->select('books.*')
            ->join('authors', 'books.author_id', '=', 'authors.id')
            ->join('categories', 'books.category_id', '=', 'categories.id')
            ->orderBy($sortField === 'author_name' ? 'authors.name' : ($sortField === 'category_name' ? 'categories.name' : $sortField), $sortOrder)
            ->with(['author', 'category']) 
            ->get();
        $authors = Authors::all();
        $categories = Categories::all();
        return view("books",compact("books", "authors", "categories", 'sortField', 'sortOrder'));
    }

    public function save(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'author_id' => 'required|exists:authors,id',
            'category_id' => 'required|exists:categories,id',
            'publication_date' => 'required|date',
            'file' => 'required|file|mimes:pdf,epub|max:2048',
        ]);
    
        $filePath = $request->file('file')->store('books', 'public');
    
        Books::create([
            'title' => $request->title,
            'author_id' => $request->author_id,
            'category_id' => $request->category_id,
            'publication_date' => $request->publication_date,
            'file_path' => $filePath,
        ]);
    
        return redirect()->back()->with('success', 'Successfully added book.');
    }

    public function download($id)
    {
        $book = Books::find($id);
        if (!$book) {
            return redirect()->back()->with('error', 'Buku tidak ditemukan.');
        }
    
        return response()->download(public_path('storage/' . $book->file_path), $book->title. "-". $book->author->name . '.pdf');
    }

    public function detail($id)
    {
        try {
            $books = Books::findOrFail($id);

            return response()->json([
                'id' => $books->id,
                "title" => $books->title,
                'author_id' => $books->author_id,
                'category_id' => $books->category_id,
                'publication_date' => $books->publication_date
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Author not found'
            ], 404);
        }
    }

    public function update(Request $request)
    {
        try {
            // Validasi input
            $validator = Validator::make($request->all(), [
                'id' => 'required|exists:books,id',
                'title' => [
                    'required',
                    'string',
                    'min:2',
                    'max:255',
                    function($attribute, $value, $fail) use ($request) {
                        // Cek apakah judul sudah ada di buku lain
                        $existingBook = Books::where('title', $value)
                            ->where('id', '!=', $request->input('id'))
                            ->first();
    
                        if ($existingBook) {
                            $fail('The book title is already taken.');
                        }
                    }
                ],
                'author_id' => 'required|exists:authors,id',
                'category_id' => 'required|exists:categories,id',
                'publication_date' => 'required|date',
                'file' => 'nullable|file|mimes:pdf,epub|max:2048',
            ], [
                'title.required' => 'Book title is required.',
                'title.min' => 'Book title must be at least 2 characters.',
                'title.max' => 'Book title cannot exceed 255 characters.',
                'author_id.exists' => 'Invalid author selected.',
                'category_id.exists' => 'Invalid category selected.',
                'publication_date.required' => 'Publication date is required.',
                'file.mimes' => 'The file must be a PDF or EPUB file.',
                'file.max' => 'The file size cannot exceed 2MB.',
            ]);
    
            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'errors' => $validator->errors()->toArray()
                ], 422);
            }
    
            $book = Books::findOrFail($request->input('id'));
            $book->title = $request->input('title');
            $book->author_id = $request->input('author_id');
            $book->category_id = $request->input('category_id');
            $book->publication_date = $request->input('publication_date');
    
            if ($request->hasFile('file')) {
                // Hapus file lama jika ada
                if (file_exists("storage/".$book->file_path)) {
                    Storage::disk('public')->delete($book->file_path);
                }
                $filePath = $request->file('file')->store('books', 'public');
                $book->file_path = $filePath;
            }
    
            $book->save();
    
            return response()->json([
                'status' => 'success',
                'message' => 'Book updated successfully',
                'data' => $book
            ]);
    
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An unexpected error occurred',
                'error_details' => $e->getMessage()
            ], 500);
        }
    }

}

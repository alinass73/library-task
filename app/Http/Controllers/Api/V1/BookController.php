<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\BookResource;
use App\Models\Book;
use Illuminate\Http\Request;
use Smalot\PdfParser\Parser;
use App\Http\Controllers\Controller;
use App\Http\Requests\BookStoreRequest;
use App\Http\Requests\BookUpdateRequest;

class BookController extends Controller
{
    public function getPdfPath(Request $request)
    {
        return $request->file('path')->store('pdfs','public');
    }
    public function store(BookStoreRequest $request)
    {
        try{
            
            
            //check if the inserter is an admin '1' or a data entry '2'
            // if(auth()->user()->role->name=='admin' || auth()->user()->role->name=='data_entry')
            $request->validated();
            if(auth()->user()->role_id==1 || auth()->user()->role_id==2)
            {
                $pdfPath= $this->getPdfPath($request);
                $book=auth()->user()->books()->create([
                    'name'=>$request->name,
                    'description'=>$request->description,
                    'path'=>$pdfPath,
                ]);
                return response()->json([
                    'book' => $book
                ], 201);
            }
            else {
                return response()->json(['message' => 'You don\'t have permission to insert a book'], 401);
            }
            
        }catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function index()
    {
        $books= Book::orderBy('name')->paginate(10);
        return BookResource::collection($books);
    }
    
    public function show(Book $book)
    {
        try{
            $pathToFile='storage/'.$book->path;
            $book->reads()->create(['user_id'=>auth()->user()->id]);
            return response()->file($pathToFile);
        }catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function update(Book $book, Request $request)
    {
        try{
            $request->validate([
                'name'=>['string','min:2','max:60'],
                'description'=>['string','min:4','max:120'],
                // 'path'=>['mimes:pdf']
            ]);
            // $request->validated();
            //check if the inserter is an admin '1' or a data entry '2'
            // if(auth()->user()->role->name=='admin' || auth()->user()->role->name=='data_entry')
            if(auth()->user()->role_id==1 || auth()->user()->role_id==2)
            {
                $book->update($request->only(['name','description']));
                return new BookResource($book);
            }
            else
            {
                return response()->json(['status' => false,'message' => 'You don\'t have permission to update this book'], 401);
            }
        }catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function destroy(Book $book)
    {
        //check if the inserter is an admin '1' or a data entry '2'
        // if(auth()->user()->role->name=='admin' || auth()->user()->role->name=='data_entry')
        if(auth()->user()->role_id==1 || auth()->user()->role_id==2)
        {
            $book->delete();
            return response()->json([
                'status'=>true,
                'message'=>'the delete process is Successfully'
            ]);
        }
        else
        return response()->json([
            'status'=>false,
            'message'=>'You don\'t have permission to delete this book',
        ]);
    }

}

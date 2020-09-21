<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $books = Book::query();

        if ($request->name) {
            $books->where('name', 'like', "%{$request->name}%");
        }

        return response($books->simplePaginate(3));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * @throws \Throwable
     */
    public function store(Request $request)
    {
        $book = new Book();
        $data = $request->only($book->getFillable());

        $validator = Validator::make($data, [
            'name'         => 'required|max:255',
            'description'  => 'required|max:2000',
            'publish_year' => 'required|date_format:Y',
        ]);

        if ($validator->fails()) {
            return response(['message' => $validator->errors()]);
        }

        $book->fill($data)->saveOrFail();

        return response('', 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function show(Book $book)
    {
        return response($book);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Book $book)
    {
        $data = $request->only($book->getFillable());

        $validator = Validator::make($data, [
            'name'         => 'max:255',
            'description'  => 'max:2000',
            'publish_year' => 'date_format:Y',
        ]);

        if ($validator->fails()) {
            return response(['message' => $validator->errors()]);
        }

        $book->fill($data)->saveOrFail();

        return response('', 204);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function destroy(Book $book)
    {
        // todo так же удалять связи с авторами
        // todo можно сделать soft_delete
        $isDeleted = $book->delete();
        return response('', $isDeleted ? 204 : 404);
    }
}

<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\Book as BookResource;
use App\Http\Resources\BookCollection;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  Request  $request
     * @return BookCollection
     */
    public function index(Request $request): BookCollection
    {
        $books = Book::query();

        if ($request->name) {
            $books->where('name', 'like', "%{$request->name}%");
        }

        return new BookCollection($books->simplePaginate(3));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     * @throws \Throwable
     */
    public function store(Request $request): Response
    {
        $book = new Book();
        $data = $request->only($book->getFillable());

        $validator = Validator::make($data, [
            'name'         => 'required|max:255',
            'description'  => 'required|max:2000',
            'publish_year' => 'required|date_format:Y',
        ]);

        if ($validator->fails()) {
            $response = response(['message' => $validator->errors()]);
        } else {
            $book->fill($data)->saveOrFail();

            $response = response('', 201);
        }

        return $response;
    }

    /**
     * Display the specified resource.
     *
     * @param  Book  $book
     * @return BookResource
     */
    public function show(Book $book): BookResource
    {
        return new BookResource($book);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  Book  $book
     * @return Response
     * @throws \Throwable
     */
    public function update(Request $request, Book $book): Response
    {
        $data = $request->only($book->getFillable());

        $validator = Validator::make($data, [
            'name'         => 'max:255',
            'description'  => 'max:2000',
            'publish_year' => 'date_format:Y',
        ]);

        if ($validator->fails()) {
            $response = response(['message' => $validator->errors()]);
        } else {
            $book->fill($data)->saveOrFail();

            $response = response('', 204);
        }

        return $response;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Book  $book
     * @return Response
     * @throws \Exception
     */
    public function destroy(Book $book)
    {
        $book->authors()->detach();
        $isDeleted = $book->delete();

        return response('', $isDeleted ? 204 : 404);
    }
}

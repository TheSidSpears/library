<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\Author as AuthorResource;
use App\Http\Resources\AuthorCollection;
use App\Models\Author;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class AuthorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  Request  $request  $request
     * @return AuthorCollection
     */
    public function index(Request $request): AuthorCollection
    {
        $authors = Author::query();

        if ($request->name) {
            $authors->where('name', 'like', "%{$request->name}%");
        }

        return new AuthorCollection($authors->simplePaginate(3));
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
        $author = new Author();
        $data = $request->only($author->getFillable());

        $validator = Validator::make($data, [
            'name'        => 'required|max:255',
            'second_name' => 'required|max:255',
            'patronymic'  => 'max:255',
            'birth_year'  => 'required|date_format:Y',
            'death_year'  => 'date_format:Y',
        ]);

        if ($validator->fails()) {
            return response(['message' => $validator->errors()]);
        }

        $author->fill($data)->saveOrFail();

        return response('', 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  Author  $author
     * @return AuthorResource
     */
    public function show(Author $author): AuthorResource
    {
        return new AuthorResource($author);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  Author  $author
     * @return Response
     * @throws \Throwable
     */
    public function update(Request $request, Author $author): Response
    {
        $data = $request->only($author->getFillable());

        $validator = Validator::make($data, [
            'name'        => 'max:255',
            'second_name' => 'max:255',
            'patronymic'  => 'nullable|max:255',
            'birth_year'  => 'date_format:Y',
            'death_year'  => 'nullable|date_format:Y',
        ]);

        if ($validator->fails()) {
            return response(['message' => $validator->errors()]);
        }

        $author->fill($data)->saveOrFail();

        return response('', 204);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Author  $author
     * @return Response
     * @throws \Exception
     */
    public function destroy(Author $author): Response
    {
        // todo так же удалять связи с книгами
        // todo можно сделать soft_delete
        $isDeleted = $author->delete();
        return response('', $isDeleted ? 204 : 404);    }
}

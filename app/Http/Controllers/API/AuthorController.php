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

        return new AuthorCollection($authors->simplePaginate(config('api.pagination_size')));
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
            $response = response(['message' => $validator->errors()]);
        } else {
            $author->fill($data)->saveOrFail();

            $response = response('', 201);
        }

        return $response;
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
            $response = response(['message' => $validator->errors()]);
        } else {
            $author->fill($data)->saveOrFail();

            $response = response('', 204);
        }

        return $response;
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
        $author->books()->detach();
        $isDeleted = $author->delete();

        return response('', $isDeleted ? 204 : 404);
    }
}

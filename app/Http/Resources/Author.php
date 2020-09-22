<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Author extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $author = parent::toArray($request);

        return array_merge($author, [
            'books_count' => $this->books()->count()
        ]);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'description', 'publish_year',
    ];

    protected $hidden = [
        'created_at', 'updated_at',
    ];

    /**
     * @return BelongsToMany
     */
    public function authors(): BelongsToMany
    {
        return $this->belongsToMany(Author::class);
    }

    /**
     * @return string
     */
    public function authorsInitials(): string
    {
        $this->authors()->each(function ($author) use (&$initials) {
            $initials[] = rtrim($author->second_name.' '.
                $author->name[0].'. '.
                ($author->patronymic ? $author->patronymic[0].'.' : ''));
        });

        return implode(', ', $initials);
    }
}

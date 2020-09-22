<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'second_name', 'patronymic', 'birth_year', 'death_year',
    ];

    protected $hidden = [
        'created_at', 'updated_at',
    ];

    public function books()
    {
        $this->belongsToMany(Book::class)
            ->as('authorship');
    }
}

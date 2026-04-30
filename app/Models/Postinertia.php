<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Postinertia extends Model
{
    /** @use HasFactory<\Database\Factories\PostinertiaFactory> */
    use HasFactory;
    protected $fillable = ['title', 'author', 'content'];
}

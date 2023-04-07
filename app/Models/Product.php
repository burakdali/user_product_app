<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'name', 'image', 'description', 'slug'
    ];

    public function id(): string
    {
        return (string) $this->id;
    }
    public function name(): string
    {
        return (string) $this->name;
    }
    public function slug(): string
    {
        return (string) $this->slug;
    }
    public function image(): string
    {
        return (string) $this->image;
    }
    public function description(): string
    {
        return (string) $this->description;
    }
}

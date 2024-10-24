<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Language extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'canonical',
        'image',
        'publish',
        'user_id',
        'current',
    ];

    protected $table = 'languages';

    public function languages() {
        return $this->belongsToMany(PostCatalogue::class, 'post_catalogue_language', 'language_id', 'post_catalogue_id')
        ->withPivot(
            'name',
            'canonical',
            'meta_title',
            'meta_keyword',
            'meta_description',
            'description',
            'content'
        )->withTimestamps();
    }
    // public function wards() {
    //     return $this->hasMany(Ward::class, 'language_id', 'id');
    // }
}

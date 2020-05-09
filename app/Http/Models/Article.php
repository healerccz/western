<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    /**
     * table name
     *
     * @var string
     */
    protected $table = 'articles';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'node_id', 'title', 'tag', 'type', 'picture',
        'cnt', 'author', 'abstract', 'deleted_at', 'post_time',
        'is_posted', 'created_at', 'updated_at'
    ];

    /**
     * write time automatically
     * @var bool
     */
    public $timestamps = true;
}

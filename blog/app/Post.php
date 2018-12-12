<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    // protected $table = 'posts';                  //To join the database table that you want to take an object
    // protected $primaryKey = 'post_id';          //To define your primary key
    //YOu don't need these properties if your model has single capital name from table posts=>Post
    //and the default primary key is id 

    protected $fillable = [
        'title','content'
    ];
}

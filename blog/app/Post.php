<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    // protected $table = 'posts';                  //To join the database table that you want to take an object
    // protected $primaryKey = 'post_id';          //To define your primary key
    //YOu don't need these properties if your model has single capital name from table posts=>Post
    //and the default primary key is id 
    use SoftDeletes;
    
    protected $fillable = [
        'title','content'
    ];
    protected $dates = ['deleted_at'];             //To add this property in your model

    public function user(){
        return $this->belongsTo('App\User');
    }
    
}

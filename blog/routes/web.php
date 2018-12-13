<?php
use App\Post;
use App\User;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

//Route::resource('/post','PostController');              //To create routes for all your controller method 

Route::get('/contact','PostController@contact');

Route::get('/show/{id}/{name}/{password}','PostController@showPost');




/*
|--------------------------------------------------------------------------
| DataBase SQL Raw Quiries
|--------------------------------------------------------------------------
*/

Route::get('/insert',function(){
    DB::insert('insert into posts(title,content) values (?,?)',['PHP with Laravel','Laravel is the best thing has happened to PHP']);
});

Route::get('/read',function(){
    $results =  DB::select('select * from posts where id=?',[1]);
    foreach ($results as $post) {
        return $post->title;
    }
});

Route::get('/update',function(){
    $update = DB::update('update posts set title=? where id =?',['HI MAN',1]);
    return $update; 
});

Route::get('/delete',function(){
    $delete = DB::delete('delete from posts where id =?',[1]);
    return $delete;
});

/*
|--------------------------------------------------------------------------
| Eloquent
|--------------------------------------------------------------------------
*/

Route::get('/posts',function(){
    $posts = Post::all();
    foreach($posts as $post){
        echo "Title is : " . $post->title . "<hr>" . "Content is : " . $post->content ."<hr>";
    }
    
});

Route::get('/user/{id}',function($id){
    $user = User::find($id)->first();
    return $user;
});
Route::get('/post/{id}',function($id){
    $user = User::find($id)->first();
    return $user;
    //echo  $user->name . "'s post title is: " . $user->post()->title . "<hr>" . "And his content is : " . $user->post()->content ."<hr>";
});

Route::get('/find/{id}',function($id){
    $post = Post::find($id);
    echo "Title is : " . $post->title . "<hr>" . "Content is : " . $post->content ."<hr>";
});

Route::get('/findOrFail/{id}',function($id){
    $post = Post::findOrFail($id);
    echo "Title is : " . $post->title . "<hr>" . "Content is : " . $post->content ."<hr>";
});

Route::get('/basicInsert',function(){
    $post = new Post;
    $post->title = 'New Post\'s title';
    $post->content = 'New Post\'s Content';
    $post->save();
});

Route::get('/basicUpdate/{id}',function($id){
    $post = Post::findOrFail($id);
    $post->title = 'New Updated Post\'s title';
    $post->content = 'New Updated Post\'s Content';
    $post->save();
});

Route::get('/create',function(){

    // $post = new Post([
    //     'title'=>'new post created',
    //     'content'=>'content of new post created'
    // ]);
    // $post->save();

    Post::create([
        'title'=>'new post created',
        'content'=>'content of new post created'
    ]);

});

Route::get('/update/{id}',function($id){
    Post::where('id',$id)->update([
        'title'=>'Title has been updated',
        'content'=>'Content has been updated'
    ]);
});

Route::get('/delete/{id}',function($id){
    // $post = Post::find($id);
    // $post->delete();
    Post::destroy($id);
});

Route::get('/softdelete/{id}',function($id){
    Post::findOrFail($id)->delete();
});

Route::get('/gettrashed',function(){
    $posts = Post::onlyTrashed()->get();
    foreach($posts as $post){
        echo 'The Title is: ' . $post->title . '<hr>' . 'The Content is: ' . $post->content . '<hr>';
    }
});

Route::get('/getallposts',function(){
    $posts = Post::withTrashed()->get();
    foreach($posts as $post){
        echo 'The Title is: ' . $post->title . '<hr>' 
        . 'The Content is: ' . $post->content . '<hr>'
        . 'Status: ' . ($post->deleted_at? 'Trashed':'Not Trashed'). '<hr>';
    }
});

Route::get('/restore/{id}',function($id){
    Post::withTrashed()->where('id',$id)->restore();
});

Route ::get('/forcedelete/{id}',function($id){
    Post::withTrashed()->where('id',$id)->forceDelete();
});
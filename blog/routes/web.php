<?php
use App\Post;
use App\User;
use App\Role;
use App\Country;

use Illuminate\Support\Facades\DB;
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
    $user = User::findOrFail($id);
    return $user;
});

Route::get('/find/{id}',function($id){
    $post = Post::find($id);
    echo "Title is : " . $post->title . "<hr>" . "Content is : " . $post->content ."<hr>";
});

Route::get('/post/{id}',function($id){
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


/*
|--------------------------------------------------------------------------
| Eloquent Relationship
|--------------------------------------------------------------------------
*/

//One-To-One Relationship

Route::get('/user/{id}/post',function($id){
    $user = User::findOrFail($id);
    echo  $user->name . "'s post title is: " . $user->post->title . "<hr>" . "And his content is : " . $user->post->content ."<hr>";
});

//Inverse case 

Route::get('/post/{id}/user',function($id){
    $post = Post::findOrFail($id);
    echo 'This post belongs to ' . $post->user->name;
});

/*-----------------------------------------------------------------*/

//One-To-Many Relationship

Route::get('/user/{id}/posts',function($id){
    $user = User::findOrFail($id);
    foreach($user->posts as $post){
        echo $post->title . "<hr>" . $post->content . "<hr>";
    }
});

//Many-To-Many Relationship

Route::get('/user/{id}/role',function($id){
    $user = User::findOrFail($id);
    $roleName = DB::table('roles')
    ->join('role_user','roles.id','role_user.role_id')
    ->select('roles.name')
    ->where('role_user.user_id',$id)
    ->first();
    echo 'The user: ' . $user->name . ' has role as: ' .  $roleName->name . PHP_EOL ;
    ///////////////////////////////////////
    // $i = 0 ;                          //
    // while($i<count($user->roles)){    //
    //     echo $user->roles[$i]->name;  //
    //     $i++;                         //
    // }                                 //
    // foreach ($user->roles as $role) { //
    //     echo $role->name;             //
    // }                                 //
    ///////////////////////////////////////

});

//Accessing Pivot Table
Route::get('/user/{id}/rolePivot',function($id){
    $user = User::findOrFail($id);
    //$role_id =  $user->roles->first()->pivot->role_id;
    // $role = Role::findOrFail($role_id);
    // echo "the role for this user is: " . $role->name  ;
    echo $user->roles->first()->pivot->created_at;
});

////////////////////////////////////////////

Route::get('/user/country/{id}',function($id){
    $country = Country::findOrFail($id);
    foreach($country->posts as $post){
        echo $post->title . "<hr>" . $post->content . "<hr>";
    }
});
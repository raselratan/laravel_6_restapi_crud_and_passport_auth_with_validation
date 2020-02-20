# Laravel passport 
What is Passport?
APIs typically use tokens to authenticate users and do not maintain session state between requests. 
Laravel makes API authentication a breeze using Laravel Passport, which provides a full OAuth2 server implementation 
for your Laravel application development in a matter of minutes.

### You have to just follow a few steps to get following web services
##### Login API
##### Register API
##### Details API




## Getting Started

### Step 1: Install Package
```Clone the repo git clone https://github.com/raselratan/laravel_6_restapi_crud_and_passport_auth_with_validation.git```
```Run composer install```
```Save as the .env.example to .env and set your database information```
```Run php artisan key:generate to generate the app key```
```Run npm install```
```Run $ php artisan migrate```
```Done !!!```
```` composer require laravel/passport ````

## open config/app.php file and add service provider.

```javascript 

config/app.php
'providers' =>[
Laravel\Passport\PassportServiceProvider::class,
],

````

## Step 2: Run Migration and Install

```javascript 

php artisan migrate
php artisan passport:install


````


## Step 3: Passport Configuration  app/User.php

```javascript 

<?php
namespace App;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
class User extends Authenticatable
{
  use HasApiTokens, Notifiable;
/**
* The attributes that are mass assignable.
*
* @var array
*/
protected $fillable = [
'name', 'email', 'password',
];
/**
* The attributes that should be hidden for arrays.
*
* @var array
*/
protected $hidden = [
'password', 'remember_token',
];
}

````


## app/Providers/AuthServiceProvider.php



```javascript 

<?php
namespace App\Providers;
use Laravel\Passport\Passport; 
use Illuminate\Support\Facades\Gate; 
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
class AuthServiceProvider extends ServiceProvider 
{ 
    /** 
     * The policy mappings for the application. 
     * 
     * @var array 
     */ 
    protected $policies = [ 
        'App\Model' => 'App\Policies\ModelPolicy', 
    ];
/** 
     * Register any authentication / authorization services. 
     * 
     * @return void 
     */ 
    public function boot() 
    { 
        $this->registerPolicies();
        Passport::routes();
        Passport::tokensExpireIn(now()->addDays(1));
        Passport::refreshTokensExpireIn(now()->addDays(1));
    } 
}

````

## Step 4 :config/auth.php

```javascript 

<?php
return [
'guards' => [ 
        'web' => [ 
            'driver' => 'session', 
            'provider' => 'users', 
        ], 
        'api' => [ 
            'driver' => 'passport', 
            'provider' => 'users', 
        ], 
    ],

````
## Step 5: Create API Route

```javascript 

<?php
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('login', 'API\AllUsers\UserController@login');
Route::post('register', 'API\AllUsers\UserController@register');

Route::group(['middleware' => 'auth:api'], function(){
    Route::post('addproducts','API\Products\ProductController@createProduct');
    Route::PUT('updateproduct/{id}','API\Products\ProductController@updateProduct');
    Route::delete('deleteproduct/{id}','API\Products\ProductController@deleteProduct');
    Route::get('allproducts','API\Products\ProductController@index');
    Route::get('list','API\AllUsers\UserController@Users');

});

````


## Step 6: Create the Controller

```javascript 

<?php
namespace App\Http\Controllers\API\AllUsers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Auth;
use Validator;
class UserController extends Controller
{
    public $successStatus = 200;
    /**
     * login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login()
    {
        if (Auth::attempt(['email' => request('email') , 'password' => request('password') ]))
        {
            $user = Auth::user();
            $success['token'] = $user->createToken('MyApp')->accessToken;
            return response()->json(['success' => $success], $this->successStatus);
        }
        else
        {
            return response()
                ->json(['error' => 'Unauthorised'], 401);
        }
    }
    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all() , [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'c_password' => 'required|same:password',
            ]);
        if ($validator->fails())
        {
            return response()
                ->json(['error' => $validator->errors() ], 401);
        }
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $success['token'] = $user->createToken('MyApp')->accessToken;
        $success['name'] = $user->name;

        return response()->json(['success' => $success], $this->successStatus);
    }
    /**
     * details api
     *
     * @return \Illuminate\Http\Response
     */
    public function details()
    {
        $user = Auth::user();
        return response()->json(['success' => $user], $this->successStatus);
    }

    public function Users(){
        return ['name'=>'Rasel'];
    }
}




````
## Step 7: Run 

```javascript 

php artisan serve



````

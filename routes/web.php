<?php

    /** @var Router $router */

    use App\Http\Controllers\AuthController;
    use Illuminate\Support\Facades\Mail;
    use Illuminate\Mail\Message;
    use Laravel\Lumen\Routing\Router;


//generate api key teste
    $router->get('/key', function () {
        return \Illuminate\Support\Str::random(32);
    });

//Auth Route
    $router->post('/login/{provider}', ['as' => 'login', 'uses' => 'AuthController@login']);

//User routes
    $router->group(['middleware' => 'auth'], function () use ($router) {
        $router->get('user', ['as' => 'getUserbyId', 'uses' => 'UserController@getUsers']);
        $router->get('user/{id}', ['as' => 'getUserbyId', 'uses' => 'UserController@getUserById']);
        $router->get('user/info/account', ['as' => 'getUserByToken', 'uses' => 'UserController@getUserByToken']);
    });

//Wallet routes
    $router->group(['middleware' => 'auth'], function () use ($router) {
        $router->get('wallet', ['as' => 'getWallets', 'uses' => 'WalletController@getWallets']);
        $router->post('wallet', ['as' => 'postWallet', 'uses' => 'WalletController@postWallet']);
        $router->get('wallet/{id}', ['as' => 'getWalletById', 'uses' => 'WalletController@getWalletById']);
    });

//transfer
    $router->post('transfer', ['as' => 'postTransfer', 'uses' => 'TransferController@postTransfer']);

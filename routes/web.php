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
        $router->post('user/transfer', ['as' => 'postTransfer', 'uses' => 'TransferController@postTransfer']);
    });

//Wallet routes
    $router->group(['middleware' => 'auth'], function () use ($router) {
        $router->get('wallet', ['as' => 'getWallets', 'uses' => 'WalletController@getWallets']);
        $router->post('wallet', ['as' => 'postWallet', 'uses' => 'WalletController@postWallet']);
        $router->get('wallet/{id}', ['as' => 'getWalletById', 'uses' => 'WalletController@getWalletById']);
    });

//test Email
    $router->get('/test_mail', function () {
        $data = ['test data'];
        try {
            $arrayUser['name'] = 'teste';
            $arrayUser['email'] = 'joaoavnt@gmail.com';
            Mail::send('email.transfer.email', $arrayUser, function (Message $message) use ($arrayUser) {
                $message->to($arrayUser['email'], $arrayUser['name'])
                        ->from('joaoavnt@gmail.com', 'digital-bank-api')
                        ->subject('Test Mail');
            });
        } catch (\Exception $exception) {
            throw new Exception($exception);
        }
//        dd('passou');
    });

<?php

namespace App\Http\Controllers\Auth;

use App\Exceptions\BusinessLogicException;
use App\Http\Controllers\Controller;
use App\Logic\Admin\UserLogic;
use GuzzleHttp\Client;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    public $logic;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->logic = new UserLogic();
    }

    /**
     * 登陆
     * @param Request $request
     * @return \Psr\Http\Message\StreamInterface
     * @throws BusinessLogicException
     */
    public function login(Request $request)
    {
        /*****************************判断是否已登陆*****************************/
        $body = $request->all();
        $user = $this->logic->getModel(['email' => $body['email']]);
        if (!empty($user->token) && !empty($user->token->where('expires_at', '>', now())->first())) {
            throw new BusinessLogicException('用户已登陆,请勿重复登陆', 1001);
        }
        /*****************************登陆*****************************/
        //获取配置
        $allApiConfig = config('services.allApi');
        $httpClient = new Client();
        //获取token
        $response = $httpClient->post($allApiConfig['tokenCallback'], [
            'form_params' => [
                'grant_type' => $allApiConfig['grant_type'],
                'client_id' => $allApiConfig['client_id'],
                'client_secret' => $allApiConfig['client_secret'],
                'scope' => $allApiConfig['scope'],
                'username' => $request->input('email'),
                'password' => $request->input('password'),
            ]
        ]);
        return $response->getBody();
    }

    public function logout(Request $request)
    {
        if ($this->guard()->check()) {
            $this->guard()->user()->token()->delete();
        }
        return $this->loggedOut($request);
    }

    public function loggedOut(Request $request)
    {
        return 'logout successful';
    }

    protected function guard()
    {
        return Auth::guard('api');
    }
}

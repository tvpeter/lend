<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Services\AzureService;
use App\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Microsoft\Graph\Graph;
use Microsoft\Graph\Model;

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
    protected $redirectTo = RouteServiceProvider::HOME;

    private $azureService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(AzureService $azureService)
    {
        $this->middleware('guest')->except('logout');

        $this->azureService = $azureService;
    }

    public function showAdminLogin()
    {
        return view('auth.admin-login');
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request)
    {
        if ($request->fullUrl() == route('login')) {
            $client = $this->azureService->client();

            $authUrl = $client->getAuthorizationUrl();

            return redirect()->away($authUrl);
        }

        $this->validateLogin($request);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if (method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);

    }

    public function callback(Request $request)
    {
        // Authorization code should be in the "code" query param
        $authCode = $request->query('code');

        if (isset($authCode)) {
            try {
                // Make the token request
                $accessToken = $this->azureService->client()->getAccessToken('authorization_code', [
                    'code' => $authCode,
                ]);

                $graph = new Graph();
                $graph->setAccessToken($accessToken->getToken());

                $user = $graph->createRequest('GET', '/me')
                    ->setReturnType(Model\User::class)
                    ->execute();
                    
                $user = User::firstWhere('email', $user->getMail());
                    
                if (!$user){
                    return redirect()->route('login')->with('message', messageResponse('danger', 'You are not a staff on bamboo, please contact IT Support.'));
                }
                
                if(!$user->active){
                    return redirect()->route('login')->with('message', messageResponse('danger', 'Your account is not active, please contact IT Support.'));
                }
                
                Auth::login($user);
                
                return redirect()->route('home');
                
            } catch (\Exception $e) {
                return redirect()->route('login')->with('message', messageResponse('danger', 'An error occurred, please try again.'));
            }
        }

        return redirect()->route('login')->with('message', messageResponse('danger', 'An error occurred, please try again.'));
    }
}

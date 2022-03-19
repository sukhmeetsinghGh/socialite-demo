<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Socialite;
use App\Models\User;
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
    protected $redirectTo = '\users';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function googleLogin()
    {
        return Socialite::driver('google')->redirect();
        //dd('here');
    }

    public function googleCallback()
    {
        try{
            $user = Socialite::driver('google')->user();
            $userDetails = User::firstOrNew(['email' => $user->getEmail()]);
            $userDetails->google_token = $user->getId();
            $userDetails->name =$user->getName();
            $userDetails->email =$user->getEmail();
            $userDetails->status = '1';
            $userDetails->save();

            \Auth::loginUsingId($userDetails->id);
            return redirect()->route('user.index');
        }catch(\Exception $e){
            //dd($e);
            abort(404);
        }
    }
}

<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Models\Publisher;
use App\Models\Advertizer;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Auth;
class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    //protected $redirectTo = '/home';
    public function redirectTo()
    {

       if (Auth::user()->usertype =='pp')
       {
           return route('publisherhome');
       }
       else
       {
           return route('advertizerhome');
       }

    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users|unique:publishers',
            'phone' => 'required|string|max:25',
            'password' => 'required|string|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data,$id=null)
    {
        //I created both the pub and advertizer on reg
       
        

        if( $data['usertype'] == 'pp')
         {
       
            Publisher::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'referal'=>$data['referal'],
            ]);

        }
        elseif($data['usertype'] == 'aa')
        {
            Advertizer::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'referal'=>$data['referal'],
                
            ]);
        }

        $user= User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'usertype' => $data['usertype'],
            'password' => bcrypt($data['password']),
        ]);

        return $user;
    }
}

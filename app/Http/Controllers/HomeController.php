<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use Auth;
use View;
use DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        //Array initialization
        $data = array();
        $profileData = array();
        
        //Check if the user is logged in
        if (Auth::check()) {
            
            //Fetching the details of logged in user
            $data = Auth::user();
            
            //Fetching the userid
            $userId = $data->id;
            
            //Fetch the details from profiles table
            $profileData = DB::table('profiles')->select('uid', 'id', 'profile_photo', 'provider')->where('user_id', $userId)->first();

            //Pass the details to view
            return View::make('user', array('data' => $data, 'profileData' => $profileData));
            //return View::make('user', array('data' => $data));
        } else {
            
            //Redirect to login page
            return view('/login');
        }
    }
}

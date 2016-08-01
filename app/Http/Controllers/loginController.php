<?php

/**
 * Controller file containing details regarding Social Media Authentication
 * @author  : Sreenath
 * @created : 31 July 2016
 * @package : laravelLoginDemo
 */
//Setting the namespace

namespace App\Http\Controllers;

//Including the required classes
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Redirect;
use App\Models\Profile as ProfileModel;
use Input;
use App\User;
use Auth;
use View;
use DB;

/**
 * Class containing all social login related functions
 * @author: Sreenath 
 * @date: 25 July, 2016
 */
class loginController extends Controller {

    /**
     * Function to connect to Google and pass the required details
     * @author: Sreenath
     * @params: null
     * @return: create an authentication url and redirect to that url
     */
    public function connectToGoogle() {

        //Setting the redirect url
        //$redirectUrl = 'http://localhost/loginDemo/public/login/google/callback';
        $redirectUrl = url('/login/google/callback');
 
        //Section to fetch the oAuth credentials
        $oauth_client_id     = env('GOOGLE_OAUTH_CLIENT_ID', '');
        $oauth_client_secret = env('GOOGLE_OAUTH_CLIENT_SECRET', '');

        //Creating new object and passing the required details
        $client = new \Google_Client();
        $client->setClientId($oauth_client_id);
        $client->setClientSecret($oauth_client_secret);
        $client->setRedirectUri($redirectUrl);

        //Setting the scope
        $client->addScope('email');
        $client->addScope('profile');

        //Setting a random value
        $state = mt_rand();
        $client->setState($state);
        $_SESSION['state'] = $state;

        //Fetch the authentication url and redirect to that page
        $authUrl = $client->createAuthUrl();
        return Redirect::to($authUrl);
    }

    /**
     * Function containing the actions to be executed after succesfull login using Google account
     * @author: Sreenath
     * @params: null
     * @return: save the details in DB and redirect to dashboard on successful login
     */
    public static function googleLoginCallBack() {

        //Fetching the oAuth credentials
        $oauth_client_id     = env('GOOGLE_OAUTH_CLIENT_ID', '');
        $oauth_client_secret = env('GOOGLE_OAUTH_CLIENT_SECRET', '');

        //Setting the redirect url
        //$redirectUrl = 'http://localhost/loginDemo/public/login/google/callback';
        $redirectUrl = url('/login/google/callback');
        
        //Creating a new object and pasing the details
        $client = new \Google_Client();
        $client->setClientId($oauth_client_id);
        $client->setClientSecret($oauth_client_secret);
        $client->setRedirectUri($redirectUrl);

        //Setting the scopes
        $client->setScopes('openid', 'profile', 'email');

        //Fetching the profile details
        $plus = new \Google_Service_Plus($client);

        //Checking if logout has been requested or not
        if (isset($_REQUEST['logout'])) {
            unset($_SESSION['access_token']);
        }

        //Fetch the code returned from Google API
        $code = Input::get('code');

        //Code Validation
        if (strlen($code) == 0)
            return Redirect::to('/')->with('message', 'There was an error communicating with Google');

        // Check if an auth token exists for the required scopes
        $tokenSessionKey = 'token-' . $client->prepareScopes();

        //Enter the loop only if code is present
        if (isset($code)) {

            //Authentication
            $client->authenticate($code);

            //Fetching the access token
            $authDtls = $client->getAccessToken();
            $accessToken = $authDtls['access_token'];

            //Entering the loop if an active access token is available
            if ($accessToken && !$client->isAccessTokenExpired()) {

                    //Passing the token to fetch the user email
                    $q = 'https://www.googleapis.com/oauth2/v1/userinfo?access_token=' . $accessToken;
                    $json = file_get_contents($q);

                    //Converting to array
                    $userInfoArray = json_decode($json, true);

                    //Fetching the values
                    $first_name  = $userInfoArray['given_name'];
                    $last_name   = $userInfoArray['family_name'];
                    $name        = $userInfoArray['name'];
                    $uid         = $userInfoArray['id'];
                    $email       = $userInfoArray['email'];
                    $profile_pic = $userInfoArray['picture'];

                    //Checking if a profile already exist with same provider (Google)
                    $profile = ProfileModel::whereUid($uid)->first();

                    //Create an new profile if no such profile exist
                    if (empty($profile)) {

                        //Create a new object
                        $user = new User;

                        //Ssetting the details and saving them in users table
                        $user->name  = $name;
                        $user->email = $email;
                        //$user->photo = 'https://graph.facebook.com/'.$me['username'].'/picture?type=large';

                        $user->save();

                        //Create new object and fetch the details for saving in profiles table
                        $profile = new ProfileModel();
                        $profile->uid = $uid;
                        $profile->profile_photo = $profile_pic;
                        $profile->provider = 'google';
                        $profile->username = $first_name;
                        $profile = $user->profile()->save($profile);
                    }

                    //Access token will be updated even if a profile already exist in the DB
                    $profile->access_token = $accessToken;
                    $profile->save();

                    $user = $profile->user;

                    //Login the user
                    Auth::login($user);

                    //Redirection after successful login
                    return Redirect::to('/')->with('message', 'Logged in with Google Credentials');
            }
        }
    }

    /**
     * Function for setting the details to be displayed after a succeful login
     * @author: Sreenath
     * @params:null
     * @return: pass required details to the View for display
     */
    public function loginSuccessDisplay() {

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
        }

        //Pass the details to view
        return View::make('user', array('data' => $data));
    }

    /**
     * Function to connect to Facebook and pass the required details
     * @author: Sreenath
     * @params: null
     * @return : fetch a login url and redirect to that page
     */
    public function connectToFacebook() {

        //Creating a new object and passing the app details
        $facebook = new \Facebook(array(
            'appId'  => env('FB_APP_ID', ''),
            'secret' => env('FB_APP_SECRET', ''),
                )
        );

        //Passing the redirect url and scope details
        $params = array(
            'redirect_uri' => url('/login/fb/callback'),
            'scope' => 'email, public_profile, user_birthday, user_location, user_work_history, user_hometown, user_photos',
        );

        //Redirecting the user
        return Redirect::to($facebook->getLoginUrl($params));
    }

    /**
     * Function containing the events to be executed on a succesfull login via Facebook
     * @author: Sreenath
     * @params: null
     * @return: save the details in DB and redirect the user to Dashboard
     */
    public function FacebookLoginCallback() {

        //Fetching the code obtained
        $code = Input::get('code');

        //Validating the code
        if (strlen($code) == 0)
            return Redirect::to('/')->with('message', 'There was an error communicating with Facebook');

        //Creating new object and passing the parameters
        $facebook = new \Facebook(array(
            'appId'  => env('FB_APP_ID', ''),
            'secret' => env('FB_APP_SECRET', ''),
                )
        );

        //Fetching the facebook userid
        $uid = $facebook->getUser();

        //Validation based on the Facebook user id
        if ($uid == 0)
            return Redirect::to('/')->with('message', 'There was an error');

        //Fetching the details of logged in user from Fb
        $me = $facebook->api('/me?fields=id,name,first_name,last_name,email,picture');

        //Checking if a Facebook profile already exist for that user in DB
        $profile = ProfileModel::whereUid($uid)->first();

        //Create a new profile if no such profile exist
        if (empty($profile)) {

            //Create a new object, pass the details and save in ueer table
            $user = new User;
            $user->name  = $me['name'];
            $user->name  = $me['first_name'] . ' ' . $me['last_name'];
            $user->email = $me['email'];
            $user->save();

            //Create a new object, pass the details and ave in profiles table
            $profile = new ProfileModel();
            $profile->uid = $uid;
            $profile->profile_photo = 'https://graph.facebook.com/' . $uid . '/picture?type=large';
            $profile->username = $me['first_name'];
            $profile->provider = 'facebook';
            $profile = $user->profile()->save($profile);
        }

        //Save the access token even if a profile already exist
        $profile->access_token = $facebook->getAccessToken();
        $profile->save();
        $user = $profile->user;

        //Login the user
        Auth::login($user);

        //Redirect to dashboard after successful login
        return Redirect::to('/')->with('message', 'Logged in with Facebook Credentials');
    }

}

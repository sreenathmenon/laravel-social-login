<?php
/**
 * File containing all unit testing functions
 * @author: Sreenath
 * @package: Laravel Login Demo
 */

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Http\Controllers\loginController;

/**
 * Class containing all testing functions related to login, registration and Password Reset 
 * @author: Sreenath
 */
class loginControllerTest extends TestCase {

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample() {
        $this->assertTrue(true);
    }

    /**
     * Checking if the redirection to user template is happening or not
     * @params: null
     * @return :void
     */
    public function testLoginSuccessDisplay() {

        $data = array('id' => 100, 'name' => 'test name', 'email' => 'zzz@test.com', 'password' => 'pass');

        //$this->assertRedirectedToRoute('views.user', $data);
    }

    /**
     * 
     * 
     * 
     */
    public function testLoginPageDisplay() {

        $this->visit('/login')
                ->see('Enter your username and password to log in:')
                ->see('Sign in!');
    }

    /**
     * Function to check if Forgot Password link is present or not
     * @params:null
     * @return :void
     */
    public function testForgotPasswordLinkDisplay() {
        $this->visit('/login')
                ->see('Forgot Your Password?');
    }

    /**
     * Function to check if Facebook login button is present
     * @params:null
     * @return: void 
     */
    public function testFacebookLoginButtonDisplay() {
        $this->visit('/login')
                ->see('Facebook');
    }

    /**
     * Function to check if Google login button is present
     * @params:null
     * @return: void 
     */
    public function testGoogleLoginButtonDisplay() {
        $this->visit('/login')
                ->see('Google');
    }

    /**
     * Function to check if Register option is present or not
     * @params:null
     * @return:void
     */
    public function testRegisterLinkDisplay() {
        $this->visit('/login')
                ->see('Register');
    }

    /**
     * Function to click the Regisster button and test it's redirection
     * @params:null
     * @return:void
     */
    public function testClickRegisterLink() {
        $this->visit('/login')
                ->click('Register')
                ->seePageIs('/register');
    }

    /**
     * Function to click on Forgot Password Link and test it's redirection 
     * @params:null
     * @return:void
     */
    public function testForgotPasswordLink() {
        $this->visit('/login')
                ->click('Forgot Your Password?')
                ->seePageIs('/password/reset');
    }

    /**
     * Function to test if register page is displayed correctly
     * @params:null
     * @return:void
     */
    public function testRegisterPageDisplay() {
        $this->visit('/register')
                ->see('Name')
                ->see('E-Mail Address')
                ->see('Password')
                ->see('Confirm Password')
                ->see('Register')
                ->dontSee('Facebook');
    }

    /**
     * Function to test if Forgot Password Page is Displayed Correctly
     * @params:null
     * @return:void
     */
    public function testForgotPasswordPageDisplay() {
        $this->visit('/password/reset')
                ->see('Reset Password')
                ->see('E-Mail Address')
                ->see(' Send Password Reset Link');
    }

    /**
     * Function to test the registration form
     * @param:null
     * @return:void
     */
    public function testRegistrationFormSubmission() {
        $this->visit('/register')
                ->type('php unit testing', 'name')
                ->type('phpunit1lt@gmail.com', 'email')
                ->type('laravel', 'password')
                ->type('laravel', 'password_confirmation')
                ->press('Register')
                ->seePageIs('/');
    }

    /**
     * Function to fill the  Login Form and test it
     * @params:null
     * @return:void
     */
    public function testLoginFormSubmission() {
        $this->visit('/login')
                ->type('sreenath.mm89@gmail.com', 'email')
                ->type('sreenath', 'password')
                ->press('Sign in!')
                ->seePageIs('/');
    }
}

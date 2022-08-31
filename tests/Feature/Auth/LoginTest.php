<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Log;

class LoginTest extends TestCase
{

    
    public function test_a_user_can_login_with_email_and_password()
    {

       $response = $this->post(route('login.api'),[
            'email' => 'meet.shahrukh10@gmail.com',
            'password' => '123456'
       ])
       ->assertOk();
    }

    public function test_if_user_email_is_not_available_then_it_return_error()
    {

       $response = $this->postJson(route('login.api'),[
            'email' => 'meet.test@gmail.com',
            'password' => '123456'
       ])
       ->assertUnprocessable();

    }

    public function test_a_user_can_register_with_this_details()
    {

       $response = $this->post(route('register.api'),[
            'name' => 'Amrin',
            'email' => 'meet.amrin10@gmail.com',
            'password' => '123456',
            'password_confirmation' => '123456'
       ])
       ->assertOk();
    }
}

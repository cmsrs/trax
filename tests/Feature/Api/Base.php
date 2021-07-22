<?php
namespace Tests\Feature\Api;

use Tests\TestCase;
use App\User;
use Illuminate\Support\Facades\Auth;

class Base extends TestCase
{
    /**
     * @test
     */
    public function createClientUser()
    {
        $user = new User([
            'email'    => 'client@email.com',
            'name'     => 'client testowy'
       ]);

        $user->password = 'client1234';

        User::where('email', 'client@email.com')->delete();
        $user->save();

        Auth::login($user);
        $this->assertTrue(Auth::check());
        $this->assertAuthenticated();
    }

}
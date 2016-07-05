<?php
/**
 * Created by PhpStorm.
 * User: fernando
 * Date: 02/07/2016
 * Time: 14:41.
 */
namespace CodeProject\OAuth;

use Auth;

class Verifier
{
    public function verify($username, $password)
    {
        $credentials = [
            'email'    => $username,
            'password' => $password,
        ];

        if (Auth::once($credentials)) {
            return Auth::user()->id;
        }

        return false;
    }
}

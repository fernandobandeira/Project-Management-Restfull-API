<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ApiTest extends TestCase
{
    use WithoutMiddleware;

    public function testProject() {
        $user = \CodeProject\Entities\User::first();

        $response = $this->actingAs($user)
            ->visit('/project');

        $this->assertEquals(200, $response->status());
    }
}

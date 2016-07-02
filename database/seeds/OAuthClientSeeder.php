<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OAuthClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('oauth_clients')->truncate();
        
        DB::table('oauth_clients')->insert(
            ['id' => 'angular_app', 'secret' => 'secret', 'name' => 'Aplicação AngularJS']
        );
    }
}

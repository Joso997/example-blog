<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;
use Laravel\Passport\Client;
use Laravel\Passport\ClientRepository;
use Laravel\Passport\Console\ClientCommand;

class OauthClientTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $clients = App::make(ClientRepository::class);

        // 1st param is the user_id - none for client credentials
        // 2nd param is the client name
        // 3rd param is the redirect URI - none for client credentials
        $clients->create(null, 'Test', 'http://blog.test/oauth/callback');
    }
}

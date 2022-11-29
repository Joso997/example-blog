<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;
use Laravel\Passport\Client;

class OAuthController extends Controller
{
    private string $client_id = '97b403ff-8a0e-48e4-8afc-3c40681c8f4e';
    private string $client_secret = 'fZtCyFdwKSXuBDeMBqRETt0llu2bnMpISz74AjdZ';

    public function redirect(){

        $queries = http_build_query([
            'client_id' => Client::first()->id,
            'redirect_uri' => Client::first()->redirect,
            'response_type' => 'code'
        ]);

        return redirect('http://blog.test/oauth/authorize?' . $queries);
    }

    public function callback(Request $request){
        $response = Http::post('http://blog.test/oauth/token',[
            'grant_type' => 'authorization_code',
            'client_id' => Client::first()->id,
            'client_secret' => Client::first()->secret,
            'redirect_uri' => Client::first()->redirect,
            'code' => $request->code
        ]);

        return Redirect::away("http://localhost:8080#" . base64_encode($response));
    }
}

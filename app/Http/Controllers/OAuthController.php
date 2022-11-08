<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;

class OAuthController extends Controller
{
    public function redirect(){

        $queries = http_build_query([
            'client_id' => '97b1d39c-307b-4095-bfdb-82c4b530022a',
            'redirect_uri' => 'http://blog.test/oauth/callback',
            'response_type' => 'code'
        ]);

        return redirect('http://blog.test/oauth/authorize?' . $queries);
    }

    public function callback(Request $request){
        $response = Http::post('http://blog.test/oauth/token',[
            'grant_type' => 'authorization_code',
            'client_id' => '97b1d39c-307b-4095-bfdb-82c4b530022a',
            'client_secret' => 'EXeMGWjiz5F8cMXBUY2NZGQV0PPp5Ejs7p50IADT',
            'redirect_uri' => 'http://blog.test/oauth/callback',
            'code' => $request->code
        ]);

        //$response = $response->json();

        return Redirect::to(URL::previous() . '#' . base64_encode($response));
    }
}

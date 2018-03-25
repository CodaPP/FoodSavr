<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Input;
use Auth;

class UserController extends Controller
{
    public function sendToken(Request $request) {

        if (!Auth::check()) {
            return json_encode([
                "STATUS" => false,
                "ERROR" => "You are not logged in."
            ]);
        }

        $token = Input::get('token');

        $userToken = DB::table('FireBaseTokens')
            ->where('ID', '=', Auth::user()->id);

        if ($userToken->exists()) {
            $userToken->update(['Token' => $token]);
        } else {
            DB::table('FireBaseTokens')->insert([
                "ID" => Auth::user()->id,
                "Token" => $token
            ]);
        }

        return json_encode([
            "STATUS" => true
        ]);
    }

    public function getToken() {

        if (!Auth::check()) {
            return json_encode([
                "STATUS" => false,
                "ERROR" => "You are not logged in."
            ]);
        }

        $userToken = DB::table('FireBaseTokens')
            ->where('ID', '=', Auth::user()->id);

        if ($userToken->exists()) {
            return json_encode([
                "STATUS" => true,
                "TOKEN" => $userToken->first()->Token
            ]);
        } else {
            return json_encode([
                "STATUS" => false
            ]);
        }
    }

    public function getRecipes() {
        $recipes = DB::table('recipes')->get();

        $response = [];

        foreach ($recipes as $recipe) {
            array_push($response, [
               "ID" => $recipe->ID,
               "Name" => $recipe->Name,
               "Ingredients" => explode("#", $recipe->Ingredients),
               "Photo" => $recipe->Photo
            ]);
        }

        return $response;
    }
}

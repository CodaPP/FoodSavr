<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 28/03/2018
 * Time: 21:01
 */

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Auth;

class ImageController extends Controller
{
    public function getPhoto(Request $photo) {
        if (!Auth::check()) {
            return [
                "STATUS" => false,
                "ERROR" => "You are not logged in."
            ];
        }

        $pathToFile = "/var/www/html/FoodSavr/resources/assets/img/" . $photo;

        return response()->file($pathToFile);
    }
}
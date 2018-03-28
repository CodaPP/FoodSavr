<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Auth;

class ProductController extends Controller
{
    public function updateInfo(Request $request) {

        if (!Auth::check()) {
            return [
                "STATUS" => false,
                "ERROR" => "You are not logged in."
            ];
        }

        $barcode = Input::get('barcode');
        $manufacturer = (Input::has('manufacturer')) ? Input::get('manufacturer') : null;
        $name = (Input::has('name')) ? Input::get('name') : null;

        $barcodeProduct = DB::table('products')
            ->where('Barcode', '=', $barcode);

        if ($barcodeProduct->exists()) {
            if ($manufacturer != null) {
                DB::table('products')
                    ->where([
                        ['Barcode', '=', $barcode]
                    ])
                    ->update(['Manufacturer' => $manufacturer]);
            }
            if ($name != null) {
                DB::table('products')
                    ->where([
                        ['Barcode', '=', $barcode]
                    ])
                    ->update(['Name' => $name]);
            }
        } else {
            return json_encode([
                "STATUS" => false,
                "ERROR" => "Acest produs nu mai exista in baza de date"
            ]);
        }

        return json_encode([
            "STATUS" => true
        ]);
    }
}

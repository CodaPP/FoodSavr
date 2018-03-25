<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Input;
use Auth;

class FridgeController extends Controller
{
    public function getUserItems() {

        if (!Auth::check()) {
            return [
                "STATUS" => false,
                "ERROR" => "You are not logged in."
            ];
        }

        $fridgeItems = DB::table('fridge')
            ->where('OwnerID', '=', Auth::user()->id)
            ->join('products', 'fridge.Barcode', '=', 'products.Barcode')
            ->get();

        $response = [
            "STATUS" => true
        ];

        $response["products"] = [];

        Carbon::setLocale('ro');

        foreach ($fridgeItems as $item) {
            array_push($response["products"], [
                "ID" => $item->ID,
                "Barcode" => $item->Barcode,
                "Manufacturer" => $item->Manufacturer,
                "Name" => $item->Name,
                "UseBy" => ($item->UseBy != null) ? Carbon::createFromTimestamp($item->UseBy)->diffForHumans(Carbon::now()) : null,
                "BestBefore" => ($item->BestBefore != null) ? Carbon::createFromTimestamp($item->BestBefore)->diffForHumans(Carbon::now()) : null,
                "Quantity" => $item->Quantity,
                "Photo" => $item->Photo
            ]);
        }

        return json_encode($response);
    }

    public function addUserItems(Request $request) {
        if (!Auth::check()) {
            return json_encode([
                "STATUS" => false,
                "ERROR" => "You are not logged in."
            ]);
        }

        $barcode = $request->input('barcode');

        $quantity = $request->input('quantity');
        $useBy = (Input::has('useby')) ? Carbon::parse($request->input('useby'))->timestamp : null;
        $bestBefore = (Input::has('bestbefore')) ? Carbon::parse($request->input('bestbefore'))->timestamp : null;

        $product = DB::table('fridge')->where([
            ['Barcode', '=', $barcode],
            ['OwnerID', '=', Auth::user()->id]
        ]);

        if ($product->exists()) {
            DB::table('fridge')
                ->where([
                    ['Barcode', '=', $barcode],
                    ['OwnerID', '=', Auth::user()->id]
                ])
                ->update(['Quantity' => $product->first()->Quantity + $quantity]);
        } else {
            DB::table('fridge')->insert(
                ['OwnerID' => Auth::user()->id, 'Barcode' => "$barcode", "UseBy" => $useBy, "BestBefore" => $bestBefore, "Quantity" => "$quantity"]
            );
        }

        $barcodeProduct = DB::table('products')->where('Barcode', '=', $barcode);

        if (!$barcodeProduct->exists()) {
            DB::table('products')->insert([
                ['Barcode' => $barcode]
            ]);
        }

        // here, we will check whether we have missing info, and prompt the user for some
        $info = DB::table('products')->where('Barcode', '=', $barcode)->first();

        $requiredInfo = [
            "barcode" => $barcode,
            "manufacturer" => $info->Manufacturer == null,
            "name" => $info->Name == null,
        ];

        return json_encode([
            "STATUS" => true,
            "requiredInfo" => $requiredInfo
        ]);
    }

    public function donateItems(Request $request) {
        if (!Auth::check()) {
            return json_encode([
                "STATUS" => false,
                "ERROR" => "You are not logged in."
            ]);
        }

        $id = Input::get('id');
        $quantity = Input::get('quantity');

        $product = DB::table('fridge')->where('ID', '=', $id);

        if ($product->exists()) {

            $product = $product->first();
            if ($product->Quantity < $quantity) $quantity = $product->Quantity;

            DB::table('donations')->insert([
                    'OwnerID' => $product->OwnerID,
                    'Barcode' => $product->Barcode,
                    'UseBy' => $product->UseBy,
                    'BestBefore' => $product->BestBefore,
                    'Quantity' => $quantity,
                    'ClaimedBy' => null
                ]
            );

            if ($product->Quantity - $quantity <= 0) {
                DB::table('fridge')->where('ID', '=', $id)->delete();
            } else {
                DB::table('fridge')
                    ->where([
                        ['ID', '=', $id]
                    ])
                    ->update(['Quantity' => $product->Quantity - $quantity]);
            }
        } else {
            return json_encode([
               "STATUS" => false,
               "ERROR" => "Aceste produse nu sunt in frigiderul tau."
            ]);
        }

        return json_encode([
            "STATUS" => true
        ]);
    }

    public function getDonatedItems() {

        if (!Auth::check()) {
            return [
                "STATUS" => false,
                "ERROR" => "You are not logged in."
            ];
        }

        $donatedItems = DB::table('donations')
            ->where('OwnerID', '=', Auth::user()->id)
            ->join('products', 'donations.Barcode', '=', 'products.Barcode')
            ->join('users', 'donations.ClaimedBy', '=', 'users.id')
            ->get();

        $response = [
            "STATUS" => true
        ];

        $response["products"] = [];

        Carbon::setLocale('ro');

        foreach ($donatedItems as $item) {
            array_push($response["products"], [
                "ID" => $item->ID,
                "Barcode" => $item->Barcode,
                "Manufacturer" => $item->Manufacturer,
                "Name" => $item->Name,
                "UseBy" => ($item->UseBy != null) ? Carbon::createFromTimestamp($item->UseBy)->diffForHumans(Carbon::now()) : null,
                "BestBefore" => ($item->BestBefore != null) ? Carbon::createFromTimestamp($item->BestBefore)->diffForHumans(Carbon::now()) : null,
                "Quantity" => $item->Quantity,
                "Photo" => $item->Photo,
                "ClaimedBy" => $item->name
            ]);
        }

        return json_encode($response);
    }


}

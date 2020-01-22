<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PalindromCtrl extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {

        return view('palindrom');
    }

    public function searchAjax(Request $request)
    {
        $rules = [
            'palindrom' => 'required',
        ];
        $this->validate($request, $rules);
        $data = preg_replace('/[^A-Za-z0-9\-]/', '', $request->palindrom);
        $data = strtolower($data);

        $rev = strrev($data);

        if($data == $rev){
            $check = 'True';
        } else{
            $check = 'False';
        }

        return response()->json([
            'message' => 'OKE',
            'data' => $check
        ]);
    }
}

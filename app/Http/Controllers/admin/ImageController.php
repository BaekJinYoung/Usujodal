<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    public function upload(Request $request)
    {
        $request->validate(['image' => 'required']);

        $fileName = $request->file('image')->getClientOriginalName();
        $path = $request->file('image')->storeAs('public/images', $fileName);
        $url = Storage::url($path);

        return response()->json(['url' => $url]);
    }
}

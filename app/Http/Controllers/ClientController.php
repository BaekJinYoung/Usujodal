<?php

namespace App\Http\Controllers;

use App\Models\Youtube;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function youtube(Request $request) {
        $youtubes = Youtube::latest()->simplePaginate(10);

        $search = $request->input('search', '');

        if (!empty($search)) {
            $youtubes->where('title', 'like', '%' . $search . '%');
        }

        return compact('youtubes', 'search');
    }
}

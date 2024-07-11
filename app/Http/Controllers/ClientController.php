<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Company;
use App\Models\Youtube;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function company(Request $request) {
        $companies = Company::latest()->simplePaginate(10);

        $search = $request->input('search', '');

        if (!empty($search)) {
            $companies->where('title', 'like', '%' . $search . '%');
        }

        return compact('companies', 'search');
    }

    public function youtube(Request $request) {
        $youtubes = Youtube::latest()->simplePaginate(10);

        $search = $request->input('search', '');

        if (!empty($search)) {
            $youtubes->where('title', 'like', '%' . $search . '%');
        }

        return compact('youtubes', 'search');
    }

    public function announcement(Request $request) {
        $announcements = Announcement::latest()->simplePaginate(10);

        $search = $request->input('search', '');

        if (!empty($search)) {
            $announcements->where('title', 'like', '%' . $search . '%');
        }

        return compact('announcements', 'search');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Company;
use App\Models\History;
use App\Models\Question;
use App\Models\Share;
use App\Models\Youtube;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function history(Request $request) {
        $histories = History::latest()->simplePaginate(10);

        return compact('histories');
    }

    public function company(Request $request) {
        $companies = Company::select('main_image', 'title', 'filter', 'created_at')
        ->latest()->simplePaginate(10);

        $search = $request->input('search', '');

        if (!empty($search)) {
            $companies->where('title', 'like', '%' . $search . '%');
        }

        return compact('companies', 'search');
    }

    public function company_detail($id) {
        $company = Company::select('id', 'main_image', 'title', 'filter', 'created_at', 'content', 'views')
            ->find($id);

        $company->increment('views');

        return compact('company');
    }

    public function youtube(Request $request) {
        $youtubes = Youtube::select('main_image', 'title', 'created_at')
        ->latest()->simplePaginate(10);

        $search = $request->input('search', '');

        if (!empty($search)) {
            $youtubes->where('title', 'like', '%' . $search . '%');
        }

        return compact('youtubes', 'search');
    }

    public function announcement(Request $request) {
        $announcements = Announcement::select('is_featured', 'title', 'created_at')
        ->latest()->simplePaginate(10);

        $search = $request->input('search', '');

        if (!empty($search)) {
            $announcements->where('title', 'like', '%' . $search . '%');
        }

        return compact('announcements', 'search');
    }

    public function share(Request $request) {
        $shares = Share::select('is_featured', 'title', 'created_at')
        ->latest()->simplePaginate(10);

        $search = $request->input('search', '');

        if (!empty($search)) {
            $shares->where('title', 'like', '%' . $search . '%');
        }

        return compact('shares', 'search');
    }

    public function question(Request $request) {
        $questions = Question::select('title')
        ->latest()->simplePaginate(10);

        $search = $request->input('search', '');

        if (!empty($search)) {
            $questions->where('title', 'like', '%' . $search . '%');
        }

        return compact('questions', 'search');
    }

}

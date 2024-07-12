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
        $companies = Company::select('id', 'main_image', 'title', 'filter', 'created_at')
        ->latest()->simplePaginate(10);

        $search = $request->input('search', '');

        if (!empty($search)) {
            $companies->where('title', 'like', '%' . $search . '%');
        }

        return compact('companies', 'search');
    }

    public function company_detail($id) {
        $company = Company::select('id', 'main_image', 'views', 'content')
            ->find($id);

        $company->increment('views');

        return response()->json(['company' => $company]);
    }

    public function youtube(Request $request) {
        $youtubes = Youtube::select('id', 'main_image', 'title', 'created_at')
        ->latest()->simplePaginate(10);

        $search = $request->input('search', '');

        if (!empty($search)) {
            $youtubes->where('title', 'like', '%' . $search . '%');
        }

        return compact('youtubes', 'search');
    }

    public function youtube_detail($id) {
        $youtube = Youtube::select('id', 'title', 'created_at', 'views', 'link', 'content')
            ->find($id);

        $youtube->increment('views');

        return compact('youtube');
    }

    public function announcement(Request $request) {
        $announcements = Announcement::select('id', 'is_featured', 'title', 'created_at')
        ->latest()->simplePaginate(10);

        $search = $request->input('search', '');

        if (!empty($search)) {
            $announcements->where('title', 'like', '%' . $search . '%');
        }

        return compact('announcements', 'search');
    }

    public function announcement_detail($id) {
        $announcement = Announcement::select('id', 'views', 'content')
            ->find($id);

        $announcement->increment('views');

        return compact('announcement');
    }

    public function share(Request $request) {
        $shares = Share::select('id', 'is_featured', 'title', 'created_at')
        ->latest()->simplePaginate(10);

        $search = $request->input('search', '');

        if (!empty($search)) {
            $shares->where('title', 'like', '%' . $search . '%');
        }

        return compact('shares', 'search');
    }

    public function share_detail($id) {
        $share = Share::select('id', 'views', 'content')
            ->find($id);

        $share->increment('views');

        return compact('share');
    }

    public function question(Request $request) {
        $questions = Question::select('id', 'title')
        ->latest()->simplePaginate(10);

        $search = $request->input('search', '');

        if (!empty($search)) {
            $questions->where('title', 'like', '%' . $search . '%');
        }

        return compact('questions', 'search');
    }

    public function question_detail($id) {
        $question = Question::select('id', 'content')
            ->find($id);

        return compact('question');

    }
}

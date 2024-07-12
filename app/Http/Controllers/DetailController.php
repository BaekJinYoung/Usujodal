<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Company;
use App\Models\Question;
use App\Models\Share;
use App\Models\Youtube;
use Illuminate\Http\Request;

class DetailController extends Controller
{
    public function company_detail($id) {
        $company = Company::select('id', 'main_image', 'views', 'content')
            ->findOrFail($id);

        $company->increment('views');

        return compact('company');
    }

    public function youtube_detail($id) {
        $youtube = Youtube::select('id', 'title', 'created_at', 'views', 'link', 'content')
            ->findOrFail($id);

        $youtube->increment('views');

        return compact('youtube');
    }

    public function announcement_detail($id) {
        $announcement = Announcement::select('id', 'views', 'content')
            ->findOrFail($id);

        $announcement->increment('views');

        return compact('announcement');
    }

    public function share_detail($id) {
        $share = Share::select('id', 'views', 'content')
            ->findOrFail($id);

        $share->increment('views');

        return compact('share');
    }

    public function question_detail($id) {
        $question = Question::select('id', 'content')
            ->findOrFail($id);

        return compact('question');
    }
}

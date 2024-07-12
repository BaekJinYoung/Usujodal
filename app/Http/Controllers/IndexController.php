<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Company;
use App\Models\History;
use App\Models\Question;
use App\Models\Share;
use App\Models\Youtube;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    // 공통 메서드: 데이터 조회 및 검색
    private function fetchData($model, $selectColumns, $searchField, $searchValue)
    {
        $query = $model::select(...$selectColumns)->latest()->simplePaginate(10);

        if (!empty($searchValue)) {
            $query->where($searchField, 'like', '%' . $searchValue . '%');
        }

        return $query;
    }

    public function history(Request $request) {
        $histories = History::latest()->simplePaginate(10);

        return compact('histories');
    }

    public function company(Request $request) {
        $search = $request->input('search', '');
        $companies = $this->fetchData(Company::class, ['id', 'main_image', 'title', 'filter', 'created_at'], 'title', $search);

        return compact('companies', 'search');
    }

    public function youtube(Request $request) {
        $search = $request->input('search', '');
        $youtubes = $this->fetchData(Youtube::class, ['id', 'main_image', 'title', 'created_at'], 'title', $search);

        return compact('youtubes', 'search');
    }

    public function announcement(Request $request) {
        $search = $request->input('search', '');
        $announcements = $this->fetchData(Announcement::class, ['id', 'is_featured', 'title', 'created_at'], 'title', $search);

        return compact('announcements', 'search');
    }

    public function share(Request $request) {
        $search = $request->input('search', '');
        $shares = $this->fetchData(Share::class, ['id', 'is_featured', 'title', 'created_at'], 'title', $search);

        return compact('shares', 'search');
    }

    public function question(Request $request) {
        $search = $request->input('search', '');
        $questions = $this->fetchData(Question::class, ['id', 'title'], 'title', $search);

        return compact('questions', 'search');
    }
}

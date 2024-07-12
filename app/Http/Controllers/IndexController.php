<?php

namespace App\Http\Controllers;

use App\Http\Resources\ApiResponse;
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
    private function fetchDataAndRespond($model, $selectColumns, $searchField, Request $request)
    {
        $search = $request->input('search', '');
        $query = $model::select($selectColumns)->latest();

        if (!empty($search)) {
            $query->where($searchField, 'like', '%' . $search . '%');
        }

        $index = $query->simplePaginate(10);

        if ($index->isEmpty()) {
            return ApiResponse::success([], '게시물이 없습니다');
        }

        return ApiResponse::success(compact('index', 'search'));
    }
    public function history(Request $request) {
        $histories = History::latest()->simplePaginate(10);

        if ($histories->isEmpty()) {
            return $this->apiResponse->error('No histories found', 404);
        }

        return $this->apiResponse->success(compact('histories'));
    }

    public function company(Request $request) {
        return $this->fetchDataAndRespond(Company::class, ['id', 'main_image', 'title', 'filter', 'created_at'], 'title', $request);
    }

    public function youtube(Request $request) {
        return $this->fetchDataAndRespond(Youtube::class, ['id', 'main_image', 'title', 'created_at'], 'title', $request);
    }

    public function announcement(Request $request) {
        return $this->fetchDataAndRespond(Announcement::class, ['id', 'is_featured', 'title', 'created_at'], 'title', $request);
    }

    public function share(Request $request) {
        return $this->fetchDataAndRespond(Share::class, ['id', 'is_featured', 'title', 'created_at'], 'title', $request);
    }

    public function question(Request $request) {
        return $this->fetchDataAndRespond(Question::class, ['id', 'title'], 'title', $request);
    }
}

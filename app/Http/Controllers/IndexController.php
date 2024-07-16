<?php

namespace App\Http\Controllers;

use App\Http\Resources\ApiResponse;
use App\Models\Announcement;
use App\Models\Company;
use App\Models\Consultant;
use App\Models\History;
use App\Models\Question;
use App\Models\Share;
use App\Models\Youtube;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class IndexController extends Controller
{
    // 공통 메서드: 데이터 조회 및 검색
    private function fetchDataAndRespond($model, $selectColumns, $searchField, $page, Request $request)
    {
        $search = $request->input('search', '');
        $query = $model::select($selectColumns)->orderBy('id', 'desc');

        // searchField가 설정된 경우 검색 기능이 있는 것으로 간주
        $hasSearchField = !is_null($searchField);

        if ($hasSearchField && !empty($search)) {
            $query->where($searchField, 'like', '%' . $search . '%');
        }

        if (method_exists($model, 'scopeWithBooleanFormatted')) {
            $query->withBooleanFormatted();
        }

        $paginationEnabled = ($page > 0);

        $index = $paginationEnabled ? $query->simplePaginate($page) : $query->get();

        if ($index->isEmpty()) {
            return ApiResponse::success([], '게시물이 없습니다');
        }

        $index->transform(function ($item) {
            if (isset($item->created_at)) {
                $item->created_at_formatted = Carbon::parse($item->created_at)->format('Y.m.d');
                unset($item->created_at);
            }
            return $item;
        });

        $responseData = $index;

        // 검색 기능이 있는 페이지의 응답 데이터에 항상 search 값을 포함
        if ($hasSearchField) {
            $responseData['search'] = $search;
        }

        return ApiResponse::success($responseData);
    }

    public function history(Request $request) {
        $histories = History::latest()->simplePaginate(10);

        if ($histories->isEmpty()) {
            return $this->apiResponse->error('No histories found', 404);
        }

        return $this->apiResponse->success(compact('histories'));
    }

    public function company(Request $request) {
        return $this->fetchDataAndRespond(Company::class, ['id', 'main_image', 'title', 'filter', 'created_at'], 'title', 10, $request);
    }

    public function youtube(Request $request) {
        return $this->fetchDataAndRespond(Youtube::class, ['id', 'main_image', 'title', 'created_at'], 'title', 9, $request);
    }

    public function consultant(Request $request) {
        return $this->fetchDataAndRespond(Consultant::class, ['id', 'main_image', 'name', 'department', 'rank', 'content'], null, 0, $request);
    }

    public function announcement(Request $request) {
        return $this->fetchDataAndRespond(Announcement::class, ['id', 'is_featured', 'title', 'created_at'], 'title', 10, $request);
    }

    public function share(Request $request) {
        return $this->fetchDataAndRespond(Share::class, ['id', 'is_featured', 'title', 'created_at'], 'title', 10, $request);
    }

    public function question(Request $request) {
        return $this->fetchDataAndRespond(Question::class, ['id', 'title', 'content'], 'title', 10, $request);
    }
}

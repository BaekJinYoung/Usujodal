<?php

namespace App\Http\Controllers;

use App\Http\Resources\ApiResponse;
use App\Models\Announcement;
use App\Models\Company;
use App\Models\Question;
use App\Models\Share;
use App\Models\Youtube;
use Illuminate\Http\Request;

class DetailController extends Controller
{
    private function detailRespond($model, $selectColumns, $id, $incrementViews = false, $prevNext = false) {
        $detail = $model::select($selectColumns)->findOrFail($id);

        if ($incrementViews) {
            $detail->increment('views');
        }

        if ($prevNext) {
            $prevDetail = $model::select('id', 'title')
                ->where('id', '<', $detail->id)
                ->orderBy('id', 'desc')
                ->first();

            $nextDetail = $model::select('id', 'title')
                ->where('id', '>', $detail->id)
                ->orderBy('id', 'asc')
                ->first();

            $detail->prev = $prevDetail ? $prevDetail : null;
            $detail->next = $nextDetail ? $nextDetail : null;
        }

        return ApiResponse::success($detail);
    }

    public function company_detail($id) {
        return $this->detailRespond(Company::class, ['id', 'main_image', 'views', 'content'], $id, true);
    }

    public function youtube_detail($id) {
        return $this->detailRespond(Youtube::class, ['id', 'title', 'created_at', 'views', 'link', 'content'], $id, true);
    }

    public function announcement_detail($id) {
        return $this->detailRespond(Announcement::class, ['id', 'views', 'content'], $id, true, true);
    }

    public function share_detail($id) {
        return $this->detailRespond(Share::class, ['id', 'views', 'content'], $id, true, true);
    }

    public function question_detail($id) {
        return $this->detailRespond(Question::class, ['id', 'content'], $id);
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Resources\ApiResponse;
use App\Models\Announcement;
use App\Models\Company;
use App\Models\Question;
use App\Models\Share;
use App\Models\Youtube;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

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

        $detail['created_at_formatted'] = Carbon::parse($detail['created_at'])->format('Y-m-d');
        unset($detail['created_at']);

        return ApiResponse::success($detail);
    }

    public function company_detail($id) {
        return $this->detailRespond(Company::class, ['id', 'title', 'main_image', 'views', 'content', 'file_path', 'created_at'], $id, true, true);
    }

    public function youtube_detail($id) {
        return $this->detailRespond(Youtube::class, ['id', 'title', 'created_at', 'views', 'link', 'content'], $id, true, true);
    }

    public function announcement_detail($id) {
        return $this->detailRespond(Announcement::class, ['id', 'title', 'views', 'content', 'image', 'file_path', 'created_at'], $id, true, true);
    }

    public function share_detail($id) {
        return $this->detailRespond(Share::class, ['id', 'title', 'views', 'content', 'image', 'file_path', 'created_at'], $id, true, true);
    }

    public function question_detail($id) {
        return $this->detailRespond(Question::class, ['id', 'content'], $id);
    }
}

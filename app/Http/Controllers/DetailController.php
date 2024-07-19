<?php

namespace App\Http\Controllers;

use App\Http\Resources\ApiResponse;
use App\Models\Announcement;
use App\Models\Company;
use App\Models\Share;
use App\Models\Youtube;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

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

        if (isset($detail->image) && Storage::exists('public/' . $detail->image)) {
            $detail->image = asset('storage/' . $detail->image);
        } else {
            $detail->image = null;
        }

        if (isset($detail->file_path) && Storage::exists('public/' . $detail->file_path)) {
            $detail->file_path = asset('storage/' . $detail->file_path);
        } else {
            $detail->file_path = null;
        }

        return ApiResponse::success($detail);
    }

    public function company_detail($id) {
        return $this->detailRespond(Company::class, ['id', 'title', 'image', 'views', 'content', 'file_path', 'created_at'], $id, true, true);
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
}

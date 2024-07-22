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

        if (array_key_exists('image', $detail->toArray())) {
            $fileExists = isset($detail->image) && Storage::exists('public/' . $detail->image);

            if ($fileExists) {
                $detail['image'] = asset('storage/' . $detail->image);
            } else {
                $detail['image'] = null;
            }
        } else {
            unset($detail['image']);
        }

        if (array_key_exists('file_path', $detail->toArray())) {
            $fileExists = isset($detail->file_path) && Storage::exists('public/' . $detail->file_path);

            if ($fileExists) {
                $detail['file_name'] = pathinfo($detail->file_path, PATHINFO_FILENAME) . '.' . pathinfo($detail->file_path, PATHINFO_EXTENSION);
                $detail['file_path'] = asset('storage/' . $detail->file_path);
            } else {
                $detail['file_name'] = null;
                $detail['file_path'] = null;
            }
        } else {
            unset($detail['file_name']);
            unset($detail['file_path']);
        }

        $fieldsOrder = [
            'id',
            'title',
            'created_at_formatted',
            'image',
            'file_name',
            'file_path',
            'prev',
            'next'
        ];

        $orderedDetail = [];
        foreach ($fieldsOrder as $field) {
            if (array_key_exists($field, $detail->toArray())) {
                $orderedDetail[$field] = $detail[$field];
            }
        }

        return ApiResponse::success($orderedDetail);
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

<?php

namespace App\Http\Controllers;

use App\Http\Resources\ApiResponse;
use App\Models\Announcement;
use App\Models\Company;
use App\Models\Share;
use App\Models\Youtube;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

class DetailController extends Controller
{
    private function convertRelativeUrlsToAbsolute($content) {
        $baseUrl = asset('storage');
        $pattern = '/<img\s+[^>]*src=["\'](\/images[^"\']+)["\']/i';
        $replacement = '<img src="' . $baseUrl . '$1"';
        return preg_replace($pattern, $replacement, $content);
    }

    private function extractYoutubeVideoId($url) {
        preg_match('/(?:https?:\/\/)?(?:www\.)?(?:youtube\.com\/(?:[^\/\n\s]+\/\S+\/|(?:v|e(?:mbed)?)\/|\S*?[?&]v=)|youtu\.be\/)([a-zA-Z0-9_-]{11})/i', $url, $matches);
        return $matches[1] ?? null;
    }

    private function detailRespond($model, $selectColumns, $id, $incrementViews = false, $prevNext = false) {
        $detail = $model::select($selectColumns)->findOrFail($id);

        if ($incrementViews) {
            $detail->increment('views');
        }

        if ($prevNext) {
            $this->setPrevNextDetails($model, $detail);
        }

        $detail['created_at_formatted'] = Carbon::parse($detail['created_at'])->format('Y-m-d');
        unset($detail['created_at']);

        $detail = $this->formatFileData($detail);

        if (isset($detail['content'])) {
            $detail['content'] = $this->convertRelativeUrlsToAbsolute($detail['content']);
        }

        if (in_array('link', $selectColumns) && isset($detail['link'])) {
            $youtubeVideoId = $this->extractYoutubeVideoId($detail['link']);
            $detail['video_id'] = $youtubeVideoId;
        }

        $fieldsOrder = [
            'id',
            'title',
            'created_at_formatted',
            'views',
            'filter',
            'content',
            'video_id',
            'image',
            'file_name',
            'file_path',
            'prev',
            'next'
        ];

        $orderedDetail = $this->reorderFields($detail, $fieldsOrder);

        return ApiResponse::success($orderedDetail);
    }

    private function setPrevNextDetails($model, $detail) {
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

    private function formatFileData($detail) {
        $detail = $this->checkFileExists($detail, 'image');
        $detail = $this->checkFileExists($detail, 'file_path');

        return $detail;
    }

    private function checkFileExists($detail, $field) {
        if (array_key_exists($field, $detail->toArray())) {
            $filePath = $detail->$field;
            $fileExists = isset($filePath) && Storage::exists('public/' . $filePath);

            if ($fileExists) {
                $detail[$field] = asset('storage/' . $filePath);
                if ($field === 'file_path') {
                    $detail['file_name'] = pathinfo($filePath, PATHINFO_FILENAME) . '.' . pathinfo($filePath, PATHINFO_EXTENSION);
                }
            } else {
                $detail[$field] = null;
                if ($field === 'file_path') {
                    $detail['file_name'] = null;
                }
            }
        } else {
            unset($detail[$field]);
            if ($field === 'file_path') {
                unset($detail['file_name']);
            }
        }

        return $detail;
    }

    private function reorderFields($detail, $fieldsOrder) {
        $orderedDetail = [];
        foreach ($fieldsOrder as $field) {
            if (array_key_exists($field, $detail->toArray())) {
                $orderedDetail[$field] = $detail[$field];
            }
        }
        return $orderedDetail;
    }

    public function downloadFile(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
            'model' => 'required|string|in:announcement,company,share'
        ]);

        // 동적으로 모델 선택
        $modelClass = $this->getModelClass($request->input('model'));

        $fileDetail = $modelClass::select(['file_path'])->findOrFail($request->input('id'));

        if (!$fileDetail->file_path || !Storage::exists('public/' . $fileDetail->file_path)) {
            return ApiResponse::error('파일이 존재하지 않습니다.', 404);
        }

        return Storage::download('public/' . $fileDetail->file_path);
    }

    private function getModelClass($model)
    {
        switch ($model) {
            case 'announcement':
                return Announcement::class;
            case 'company':
                return Company::class;
            case 'share':
                return Share::class;
            default:
                throw new \InvalidArgumentException('올바르지 않은 모델입니다.');
        }
    }

    public function company_detail($id) {
        return $this->detailRespond(Company::class, ['id', 'title', 'filter', 'image', 'views', 'content', 'file_path', 'created_at'], $id, true, true);
    }

    public function youtube_detail($id) {
        return $this->detailRespond(Youtube::class, ['id', 'title', 'created_at', 'views', 'link', 'content'], $id, true, true);
    }

    public function announcement_detail($id) {
        return $this->detailRespond(Announcement::class, ['id', 'title', 'views', 'content', 'file_path', 'created_at'], $id, true, true);
    }

    public function share_detail($id) {
        return $this->detailRespond(Share::class, ['id', 'title', 'views', 'content', 'file_path', 'created_at'], $id, true, true);
    }
}

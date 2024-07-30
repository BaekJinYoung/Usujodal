<?php

namespace App\Http\Controllers;

use App\Http\Resources\ApiResponse;
use App\Models\Announcement;
use App\Models\Banner;
use App\Models\Company;
use App\Models\Consultant;
use App\Models\History;
use App\Models\Popup;
use App\Models\Question;
use App\Models\Share;
use App\Models\YearlyImage;
use App\Models\Youtube;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

class IndexController extends Controller
{
    private function formatItem($item) {
        if (isset($item->created_at)) {
            $item->created_at_formatted = Carbon::parse($item->created_at)->format('Y.m.d');
            unset($item->created_at);
        }
        return $item;
    }

    private function formatItemWithImage($item) {
        $isBannerModel = $item instanceof Banner;

        if (isset($item->image)) {
            $item->image = asset('storage/' . $item->image);
            $fileType = $this->getFileType($item->image);
            $item->image_type = ($fileType === 'image') ? 0 : 1;
            if (!$isBannerModel) {
                unset($item->image_type);
            }
        }
        if (isset($item->mobile_image)) {
            $item->mobile_image = asset('storage/' . $item->mobile_image);
            $fileType = $this->getFileType($item->mobile_image);
            $item->mobile_image_type = ($fileType === 'image') ? 0 : 1;
        }
        return $item;
    }

    private function getFileType($filePath)
    {
        $extension = pathinfo($filePath, PATHINFO_EXTENSION);

        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'tiff'];
        $videoExtensions = ['mp4', 'mov', 'avi', 'mkv', 'webm'];

        if (in_array(strtolower($extension), $imageExtensions)) {
            return 'image';
        } elseif (in_array(strtolower($extension), $videoExtensions)) {
            return 'video';
        }

        return 'unknown';
    }

    private function formatCollection($collection) {
        return $collection->transform(function ($item) {
            return $this->formatItem($item);
        });
    }

    private function extractYoutubeVideoId($url) {
        preg_match('/(?:https?:\/\/)?(?:www\.)?(?:youtube\.com\/(?:[^\/\n\s]+\/\S+\/|(?:v|e(?:mbed)?)\/|\S*?[?&]v=)|youtu\.be\/)([a-zA-Z0-9_-]{11})/i', $url, $matches);
        return $matches[1] ?? null;
    }

    private function fetchDataAndRespond($model, $selectColumns, $searchField, $page, Request $request) {
        $search = $request->input('search', '');
        $query = $model::select($selectColumns)->orderBy('id', 'desc');

        if (!is_null($searchField) && !empty($search)) {
            $query->where($searchField, 'like', '%' . $search . '%');
        }

        if (method_exists($model, 'scopeWithBooleanFormatted')) {
            $query->withBooleanFormatted();
        }

        if ($page > 0) {
            $pagination = $query->paginate($page);
            $dataCollection = $pagination->getCollection();
        } else {
            $dataCollection = $query->get();
        }

        $isYoutubeModel = $model === Youtube::class;

        if ($isYoutubeModel) {
            $dataCollection = $dataCollection->map(function ($item) {
                if (isset($item->link)) {
                    $youtubeVideoId = $this->extractYoutubeVideoId($item->link);
                    $item->video_id = $youtubeVideoId;
                    unset($item->link);
                }
                return $this->formatItemWithImage($this->formatItem($item));
            });
        } else {
            $dataCollection = $dataCollection->map(function ($item) {
                return $this->formatItemWithImage($this->formatItem($item));
            });
        }

        if ($page > 0) {
            $pagination->setCollection($dataCollection);
            $data = $pagination->toArray();
        } else {
            $data = [
                'data' => $dataCollection,
                'total' => $dataCollection->count()
            ];
        }

        $data['search'] = $search;

        return ApiResponse::success($data);
    }

    private function fetchAndFormat($model, $selectColumns, $limit, $isFeatured = false, $sortDirection = 'desc') {
        if (!in_array($sortDirection, ['asc', 'desc'])) {
            $sortDirection = 'desc';
        }

        $query = $model::select($selectColumns)
            ->orderBy('id', $sortDirection);

        if ($isFeatured) {
            $query->where('is_featured', true);
        }

        if ($limit > 0) {
            $query->limit($limit);
        }

        $data = $query->get();

        $isYoutubeModel = $model === Youtube::class;

        $data = $data->map(function ($item) use ($isYoutubeModel) {
            if ($isYoutubeModel && isset($item->link)) {
                $youtubeVideoId = $this->extractYoutubeVideoId($item->link);
                $item->video_id = $youtubeVideoId;
                unset($item->link);
            }

            return $this->formatItemWithImage($item);
        });

        $formattedData = $this->formatCollection($data);

        if ($formattedData->isEmpty()) {
            return ApiResponse::success([], '게시물이 없습니다.');
        }

        return $formattedData;
    }

    public function mainRespond() {
        $popup = $this->fetchAndFormat(Popup::class, ['id', 'title', 'image','link'], 0);
        $banner = $this->fetchAndFormat(Banner::class, ['id', 'title', 'mobile_title', 'image', 'mobile_image'], 0, false, 'asc');
        $notice = $this->fetchAndFormat(Announcement::class, ['id', 'title'], 5, true);
        $news = $this->fetchAndFormat(Share::class, ['id', 'title'], 5, true);
        $announcements = $this->fetchAndFormat(Announcement::class, ['id', 'title', 'content', 'created_at'], 9);
        $youtubes = $this->fetchAndFormat(Youtube::class, ['id', 'title', 'link', 'created_at'], 9, true);

        $main = [
            'popup' => $popup,
            'banner' => $banner,
            'notice' => $notice,
            'news' => $news,
            'youtube' => $youtubes,
            'announcement' => $announcements,
        ];

        return ApiResponse::success($main);
    }

    public function history(Request $request) {
        $decade = $request->input('decade');

        $histories = History::orderBy('date', 'asc')->get();

        $historiesByYear = $histories->groupBy(function ($item) {
            return Carbon::parse($item->date)->format('Y');
        });

        $historiesByYear = $historiesByYear->toArray();
        krsort($historiesByYear);

        $historiesByDecade = collect($historiesByYear)->groupBy(function ($yearGroup, $year) {
            return floor($year / 10) * 10;
        })->sortKeysDesc();

        $years = [];

        foreach ($historiesByDecade as $decades => $yearGroups) {
            if ($decade === null || $decades == $decade) {
                foreach ($yearGroups as $year => $yearGroup) {

                    $year = collect($yearGroup)->map(function ($history) {
                        return substr($history['date'], 0, 4);
                    })->first();

                    $yearlyImage = YearlyImage::where('year', $year)->first();
                    $image = $yearlyImage && $yearlyImage->image ? asset('storage/' . $yearlyImage->image) : null;

                    $years[] = [
                        'year' => $year,
                        'image' => $image ? $image : null,
                        'histories' => collect($yearGroup)->map(function ($history) {
                            return [
                                'id' => $history['id'],
                                'content' => $history['content'],
                            ];
                        })->values()->all(),
                    ];
                }
            }
        }

        return ApiResponse::success(compact('decade', 'years'));
    }

    public function company(Request $request) {
        return $this->fetchDataAndRespond(Company::class, ['id', 'image', 'title', 'filter', 'created_at'], 'title', 9, $request);
    }

    public function youtube(Request $request) {
        return $this->fetchDataAndRespond(Youtube::class, ['id', 'link', 'title', 'created_at'], 'title', 9, $request);
    }

    public function consultant(Request $request) {
        return $this->fetchDataAndRespond(Consultant::class, ['id', 'image', 'name', 'department', 'rank', 'content'], null, 0, $request);
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

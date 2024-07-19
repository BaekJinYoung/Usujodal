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
        if (isset($item->image)) {
            $item->image = asset('storage/' . $item->image);
        }
        return $item;
    }

    private function formatCollection($collection) {
        return $collection->transform(function ($item) {
            return $this->formatItem($item);
        });
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

        $paginationEnabled = ($page > 0);
        $index = $paginationEnabled ? $query->simplePaginate($page) : $query->get();

        if ($index->isEmpty()) {
            if (!empty($search)) {
                return ApiResponse::success([], '검색 결과가 없습니다. 검색어: ' . $search);
            } else {
                return ApiResponse::success([], '게시물이 없습니다.');
            }
        }

        if ($paginationEnabled) {
            $index->getCollection()->transform(function ($item) {
                return $this->formatItem($item);
            });
            $index->getCollection()->transform(function ($item) {
                return $this->formatItemWithImage($item);
            });
        } else {
            $index = $this->formatCollection($index);
            $index = $index->transform(function ($item) {
                return $this->formatItemWithImage($item);
            });
        }

        $responseData = $index;

        if (!is_null($searchField)) {
            $responseData['search'] = $search;
        }

        return ApiResponse::success($responseData);
    }

    private function fetchAndFormat($model, $selectColumns, $limit, $isFeatured = false) {
        $query = $model::select($selectColumns)
            ->orderBy('created_at', 'desc');

        if ($isFeatured ) {
            $query->where('is_featured', true);
        }

        if ($limit > 0) {
            $query->limit($limit);
        }

        $data = $query->get();

        $data = $data->transform(function ($item) {
            return $this->formatItemWithImage($item);
        });

        $data = $this->formatCollection($data);

        if ($data->isEmpty()) {
            return ApiResponse::success([], '게시물이 없습니다.');
        }

        return $this->formatCollection($data);
    }

    public function mainRespond() {
        $popup = $this->fetchAndFormat(Popup::class, ['id', 'title', 'image','link'], 0);
        $banner = $this->fetchAndFormat(Banner::class, ['id', 'title', 'image', 'content'], 0);
        $notice = $this->fetchAndFormat(Announcement::class, ['id', 'title'], 5, true);
        $news = $this->fetchAndFormat(Share::class, ['id', 'title'], 5, true);
        $announcements = $this->fetchAndFormat(Announcement::class, ['id', 'title', 'content', 'created_at'], 9);
        $youtubes = $this->fetchAndFormat(Youtube::class, ['id', 'title', 'image', 'created_at'], 9, true);

        $main[] = [
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
                    $historiesWithImages = collect($yearGroup)->filter(function ($history) {
                        return !is_null($history['image']);
                    });

                    $year = collect($yearGroup)->map(function ($history) {
                        return substr($history['date'], 0, 4);
                    })->first();

                    $image = $historiesWithImages->map(function ($history) {
                        return asset('storage/' . $history['image']);
                    })->first();

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
        return $this->fetchDataAndRespond(Company::class, ['id', 'image', 'title', 'filter', 'created_at'], 'title', 10, $request);
    }

    public function youtube(Request $request) {
        return $this->fetchDataAndRespond(Youtube::class, ['id', 'image', 'title', 'created_at'], 'title', 9, $request);
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

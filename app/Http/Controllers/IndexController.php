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
    private function formatCreatedAt($collection)
    {
        return $collection->transform(function ($item) {
            if (isset($item->created_at)) {
                $item->created_at_formatted = Carbon::parse($item->created_at)->format('Y.m.d');
                unset($item->created_at);
            }
            return $item;
        });
    }

    private function fetchDataAndRespond($model, $selectColumns, $searchField, $page, Request $request)
    {
        $search = $request->input('search', '');
        $query = $model::select($selectColumns)->orderBy('id', 'desc');

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

        $index = $this->formatCreatedAt($index);

        $responseData = $index;

        if ($hasSearchField) {
            $responseData['search'] = $search;
        }

        return ApiResponse::success($responseData);
    }

    private function fetchAndFormat($model, $selectColumns, $limit, $isFeatured = false)
    {
        $query = $model::select($selectColumns)
            ->orderBy('created_at', 'desc')
            ->limit($limit);

        if ($isFeatured ) {
            $query->where('is_featured', true);
        }

        $data = $query->get();

        return $this->formatCreatedAt($data);
    }

    public function mainRespond() {
        $notice = $this->fetchAndFormat(Announcement::class, ['id', 'title'], 5, true);
        $news = $this->fetchAndFormat(Share::class, ['id', 'title'], 5, true);
        $announcements = $this->fetchAndFormat(Announcement::class, ['id', 'title', 'content', 'created_at'], 9);
        $youtubes = $this->fetchAndFormat(Youtube::class, ['id', 'title', 'main_image', 'created_at'], 9);

        $main[] = [
            'notice' => $notice,
            'news' => $news,
            'youtubes' => $youtubes,
            'announcements' => $announcements,
        ];

        return ApiResponse::success($main);
    }

    public function history($decade = null) {
        $histories = History::orderBy('date', 'asc')->get();

        $historiesByYear = $histories->groupBy(function ($item) {
            return Carbon::parse($item->date)->format('Y');
        });

        $historiesByYear = $historiesByYear->toArray();
        krsort($historiesByYear);

        $historiesByDecade = collect($historiesByYear)->groupBy(function ($yearGroup, $year) {
            $decade = floor($year / 10) * 10;
            return $decade;
        })->sortKeysDesc();

        foreach ($historiesByDecade as $decades => $yearGroups) {
            if ($decade === null || $decades == $decade) {
                foreach ($yearGroups as $year => $yearGroup) {
                    $historiesWithImages = collect($yearGroup)->filter(function ($history) {
                        return !is_null($history['image']);
                    });

                    $year = collect($yearGroup)->map(function ($history) {
                        return substr($history['date'], 0, 4);
                    })->first();
                    $image = $historiesWithImages->first();

                    $years[] = [
                        'year' => $year,
                        'image' => $image ? $image['image'] : null,
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

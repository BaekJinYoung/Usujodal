<?php

namespace App\Http\Controllers\admin;

use App\Http\Requests\HistoryRequest;
use Illuminate\Http\Request;
use App\Models\History;
use App\Models\YearlyImage;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class HistoryController extends BaseController {

    public function __construct(History $history) {
        parent::__construct($history);
    }

    public function index(Request $request)
    {
        $query = $this->model->query();
        $selectedYear = $request->query('yearFilter', '');
        $search = $request->input('search', '');

        if (!empty($search)) {
            $query->where('title', 'like', '%' . $search . '%');
        }

        if ($selectedYear) {
            $query->whereYear('date', $selectedYear);
        }

        $perPage = $request->query('perPage', 10);
        $items = $query->orderBy('date', 'desc')->paginate($perPage);

        $years = $this->model->selectRaw('YEAR(date) as year')->distinct()->pluck('year')->toArray();

        $items->getCollection()->transform(function ($item) {
            $year = Carbon::parse($item->date)->format('Y');
            $yearlyImage = YearlyImage::where('year', $year)->first();
            $item->image = $yearlyImage ? asset('storage/' . $yearlyImage->image_path) : null;
            return $item;
        });

        return view('admin.historyIndex', compact('items', 'perPage', 'years', 'selectedYear'));
    }

    public function store(HistoryRequest $request)
    {
        // 유효성 검사를 통과한 데이터
        $validated = $request->validated();

        // 날짜 변환
        if (isset($validated['date'])) {
            $date = Carbon::parse($validated['date']);
            $validated['date'] = $date->format('Y-m-d');
        }

        // 연도 추출
        $year = Carbon::parse($validated['date'])->format('Y');

        // YearlyImage 처리
        $yearlyImage = YearlyImage::firstOrNew(['year' => $year]);

        if ($request->hasFile('image')) {
            // 기존 이미지 삭제
            if ($yearlyImage->exists && $yearlyImage->image_path) {
                Storage::delete('public/' . $yearlyImage->image_path);
            }

            // 이미지 저장
            $file = $request->file('image');
            $fileName = uniqid() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('images', $fileName, 'public');
            $yearlyImage->image_path = $path;
            $yearlyImage->save();
        }

        // 연혁 항목 저장
        $this->model->create($validated);

        // 리다이렉션
        return redirect()->route('admin.historyIndex')->with('success', 'History created successfully');
    }

    public function edit($id)
    {
        $item = $this->model->find($id);

        $year = Carbon::parse($item->date)->format('Y');
        $yearlyImage = YearlyImage::where('year', $year)->first();
        $item->image = $yearlyImage ? asset('storage/' . $yearlyImage->image_path) : null;

        return view($this->getViewName('edit'), compact('item'));
    }

    public function update(HistoryRequest $request, History $history) {
        $update = $request->validated();

        $year = Carbon::parse($history->date)->format('Y');
        $yearlyImage = YearlyImage::firstOrNew(['year' => $year]);

        if ($request->hasFile('image')) {
            if ($yearlyImage->exists && $yearlyImage->image_path) {
                Storage::delete('public/' . $yearlyImage->image_path);
            }
            $fileName = $request->file('image')->getClientOriginalName();
            $path = $request->file('image')->storeAs('images', $fileName, 'public');
            $yearlyImage->image_path = $path;
            $yearlyImage->save();
        } elseif ($request->input('remove_image') == 1) {
            if ($yearlyImage->exists && $yearlyImage->image_path) {
                Storage::delete('public/' . $yearlyImage->image_path);
                $yearlyImage->image_path = null;
                $yearlyImage->save();
            }
        }

        if ($request->filled('date')) {
            $date = Carbon::parse($request->input('date'));
            $update['date'] = $date->format('Y-m-d');
        }

        $history->save();

        return redirect()->route('admin.historyIndex');
    }
}

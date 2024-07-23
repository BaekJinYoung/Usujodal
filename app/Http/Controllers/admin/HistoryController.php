<?php

namespace App\Http\Controllers\admin;

use App\Http\Requests\HistoryRequest;
use Illuminate\Http\Request;
use App\Models\History;
use App\Models\YearlyImage;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;

class HistoryController extends BaseController {

    public function __construct(History $history) {
        parent::__construct($history);
    }

    public function index(Request $request) {
        $query = $this->model->query();
        $selectedYear = $request->query('yearFilter', '');

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

    public function store(HistoryRequest $request) {
        $validated = $request->validated();

        if (isset($validated['date'])) {
            $date = Carbon::parse($validated['date']);
            $validated['date'] = $date->format('Y-m-d');
        }

        $year = Carbon::parse($validated['date'])->format('Y');

        $yearlyImage = YearlyImage::firstOrNew(['year' => $year]);

        if ($request->hasFile('image')) {

            if ($yearlyImage->exists && $yearlyImage->image_path) {
                $confirmOverwrite = $request->input('confirm_overwrite');

                if ($confirmOverwrite !== 'yes') {
                    return redirect()->back()->with('warning', '이미지가 이미 등록되어 있습니다. 이미지를 덮어쓰시겠습니까?')->withInput();
                }
            }

            if ($yearlyImage->exists && $yearlyImage->image_path) {
                Storage::delete('public/' . $yearlyImage->image_path);
            }

            $file = $request->file('image');
            $fileName = uniqid() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('images', $fileName, 'public');
            $yearlyImage->image_path = $path;
            $yearlyImage->save();
        }

        $dataToStore = Arr::except($validated, ['image']);

        $this->model->create($dataToStore);

        return redirect()->route('admin.historyIndex');
    }

    public function edit($id) {
        $item = $this->model->find($id);

        if (!$item) {
            return redirect()->route($this->getRouteName('index'))
                ->with('error', '해당 게시물을 찾을 수 없습니다.');
        }

        $year = Carbon::parse($item->date)->format('Y');
        $yearlyImage = YearlyImage::where('year', $year)->first();
        $item->image = $yearlyImage ? $yearlyImage->image_path : null;

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

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
            $confirmOverwrite = $request->input('confirm_overwrite');

            if ($yearlyImage->exists && $yearlyImage->image_path) {
                if ($confirmOverwrite === 'yes') {
                    Storage::delete('public/' . $yearlyImage->image_path);
                }
            }

            $fileName = $request->file('image')->getClientOriginalName();
            $path = $request->file('image')->storeAs('images', $fileName, 'public');
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

        if ($yearlyImage && $yearlyImage->image_path) {
            $item->image = asset('storage/' . $yearlyImage->image_path);
            $item->image_name = pathinfo($yearlyImage->image_path, PATHINFO_FILENAME) . '.' . pathinfo($yearlyImage->image_path, PATHINFO_EXTENSION);
        } else {
            $item->image = null;
            $item->image_name = null;
        }

        return view($this->getViewName('edit'), compact('item'));
    }

    public function checkImage($year) {
        $yearlyImage = YearlyImage::where('year', $year)->first();
        $imageName = $yearlyImage ? pathinfo($yearlyImage->image_path, PATHINFO_BASENAME) : null;
        return response()->json([
            'exists' => (bool) $yearlyImage,
            'imageName' => $imageName,
        ]);
    }

    public function update(HistoryRequest $request, History $history) {
        $update = $request->validated();

        if ($request->filled('date')) {
            $date = Carbon::parse($request->input('date'));
            $update['date'] = $date->format('Y-m-d');
        }

        $year = Carbon::parse($history->date)->format('Y');
        $yearlyImage = YearlyImage::firstOrNew(['year' => $year]);

        if ($request->hasFile('image')) {
            $confirmOverwrite = $request->input('confirm_overwrite');

            if ($yearlyImage->exists && $yearlyImage->image_path) {
                if ($confirmOverwrite === 'yes') {
                    Storage::delete('public/' . $yearlyImage->image_path);
                }
            }

            $fileName = $request->file('image')->getClientOriginalName();
            $path = $request->file('image')->storeAs('images', $fileName, 'public');
            $yearlyImage->image_path = $path;
            $yearlyImage->save();
        } elseif ($request->input('remove_image') == '1') {
            if ($yearlyImage->exists && $yearlyImage->image_path) {
                Storage::delete('public/' . $yearlyImage->image_path);
                $yearlyImage->image_path = null;
                $yearlyImage->save();
            }
        }

        $history->update($update);

        return redirect()->route('admin.historyIndex');
    }
}

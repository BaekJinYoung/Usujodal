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

    public function store(HistoryRequest $request) {
        $store = $request->validated();

        if ($request->filled('date')) {
            $date = Carbon::parse($request->input('date'));
            $validated['date'] = $date->format('Y-m-d');
        }

        $year = Carbon::parse($store->date)->format('Y');
        $yearlyImage = YearlyImage::firstOrNew(['year' => $year]);

        if ($request->hasFile('image')) {
            if ($yearlyImage->exists && $yearlyImage->image_path) {
                Storage::delete('public/' . $yearlyImage->image_path);
            }
            $fileName = $request->file('image')->getClientOriginalName();
            $path = $request->file('image')->store('images', $fileName, 'public');
            $yearlyImage->image_path = $path;
            $yearlyImage->save();
        }

        if ($request->filled('date')) {
            $date = Carbon::parse($request->input('date'));
            $store['date'] = $date->format('Y-m-d');
        }

        $this->model->create($store);

        if ($request->filled('continue')) {
            return redirect()->route('admin.historyIndex');
        }

        return redirect()->route('admin.historyIndex');
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

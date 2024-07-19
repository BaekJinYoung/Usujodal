<?php

namespace App\Http\Controllers\admin;

use App\Http\Requests\HistoryRequest;
use Illuminate\Http\Request;
use App\Models\History;
use App\Models\YearlyImage;
use Carbon\Carbon;

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

        if ($request->hasFile('image')) {
            $fileName = $request->file('image')->getClientOriginalName();
            $imagePath = $request->file('image')->storeAs('yearly_images', $fileName, 'public');

            $year = substr($request->input('date'), 0, 4);

            YearlyImage::updateOrCreate(
                ['year' => $year],
                ['image_path' => $imagePath]
            );
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
        $item->image = $yearlyImage->image_path;

        return view($this->getViewName('edit'), compact('item'));
    }

    public function update(HistoryRequest $request, History $history) {
        $update = $request->validated();

        if ($request->hasFile('image')) {
            $fileName = $request->file('image')->getClientOriginalName();
            $imagePath = $request->file('image')->storeAs('yearly_images', $fileName, 'public');

            $year = substr($request->input('date'), 0, 4);

            YearlyImage::updateOrCreate(
                ['year' => $year],
                ['image_path' => $imagePath]
            );
        }

        if ($request->filled('date')) {
            $date = Carbon::parse($request->input('date'));
            $update['date'] = $date->format('Y-m-d');
        }

        $history->update($update);

        return redirect()->route('admin.historyIndex');
    }
}

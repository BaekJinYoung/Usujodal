<?php

namespace App\Http\Controllers;

use App\Http\Requests\HistoryRequest;
use App\Models\History;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    public function __construct(History $history)
    {
        $this->History = $history;
    }

    public function index(Request $request) {
        $perPage = $request->query('perPage', 8);
        $selectedYear = $request->query('yearFilter', '');
        $histories = $this->History->query();

        if ($selectedYear) {
            $histories->whereYear('date', $selectedYear);
        }

        $histories = $histories->paginate($perPage);

        $years = $this->History->selectRaw('YEAR(date) as year')->distinct()->pluck('year')->toArray();

        return view('admin.historyIndex', compact('histories', 'perPage', 'years', 'selectedYear'));
    }

    public function create()
    {
        return view('admin.historyCreate');
    }

    public function store(HistoryRequest $request)
    {
        $store = $request->validated();

        if ($request->hasFile('image')) {
            $fileName = $request->file('image')->getClientOriginalName();
            $path = $request->file('image')->storeAs('images', time() . '_' . $fileName, 'public');
            $store['image'] = $path;
        }

        $date = Carbon::parse($request['registered_at']);
        $store['date'] = $date->format('Y-m-d');

        $this->History->create($store);

        if ($request->filled('continue')) {
            return redirect()->route('admin.historyIndex');
        }

        return redirect()->route('admin.historyIndex');
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\HistoryRequest;
use App\Models\History;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    public function __construct(History $history) {
        $this->History = $history;
    }

    public function index(Request $request) {
        $perPage = $request->query('perPage', 8);
        $selectedYear = $request->query('yearFilter', '');
        $histories = $this->History->query();

        if ($selectedYear) {
            $histories->whereYear('date', $selectedYear);
        }

        $histories = $histories->simplePaginate($perPage);

        $years = $this->History->selectRaw('YEAR(date) as year')->distinct()->pluck('year')->toArray();

        return compact('histories', 'perPage', 'years', 'selectedYear');
    }

//    public function index(Request $request) {
//        $perPage = $request->query('perPage', 8);
//        $selectedYear = $request->query('yearFilter', '');
//        $histories = $this->History->query();
//
//        if ($selectedYear) {
//            $histories->whereYear('date', $selectedYear);
//        }
//
//        $histories = $histories->paginate($perPage);
//
//        $years = $this->History->selectRaw('YEAR(date) as year')->distinct()->pluck('year')->toArray();
//
//        return view('admin.historyIndex', compact('histories', 'perPage', 'years', 'selectedYear'));
//    }

    public function create(){
        return view('admin.historyCreate');
    }

    public function store(HistoryRequest $request) {
        $store = $request->validated();

        if ($request->hasFile('image')) {
            $fileName = $request->file('image')->getClientOriginalName();
            $path = $request->file('image')->storeAs('images', time() . '_' . $fileName, 'public');
            $store['image'] = $path;
        }

        if ($request->filled('date')) {
            $date = Carbon::parse($request->input('date'));
            $store['date'] = $date->format('Y-m-d');
        }

        $this->History->create($store);

        if ($request->filled('continue')) {
            return redirect()->route('admin.historyIndex');
        }

        return redirect()->route('admin.historyIndex');
    }

    public function edit($id) {
        $history = $this->History->find($id);

        return view('admin.historyEdit', compact('history'));
    }

    public function update(HistoryRequest $request, History $history) {
        $update = $request->validated();

        if ($request->hasFile('image')) {
            $fileName = $request->file('image')->getClientOriginalName();
            $path = $request->file('image')->storeAs('images', time() . '_' . $fileName, 'public');
            $update['image'] = $path;
        }

        if ($request->filled('date')) {
            $date = Carbon::parse($request->input('date'));
            $update['date'] = $date->format('Y-m-d');
        }

        $history->update($update);

        return redirect()->route('admin.historyIndex');
    }

    public function delete(History $history) {
        $history->delete();
        return redirect()->route('admin.historyIndex');
    }
}

<?php

namespace App\Http\Controllers\admin;

use App\Http\Requests\HistoryRequest;
use App\Models\History;
use Carbon\Carbon;
use function App\Http\Controllers\create;

class HistoryController extends BaseController {

    public function __construct(History $history) {
        parent::__construct($history);
    }

    public function store(HistoryRequest $request) {
        $store = $request->validated();

        if ($request->hasFile('image')) {
            $fileName = $request->file('image')->getClientOriginalName();
            $path = $request->file('image')->storeAs('images', $fileName, 'public');
            $store['image'] = $path;
        }

        if ($request->filled('date')) {
            $date = Carbon::parse($request->input('date'));
            $store['date'] = $date->format('Y-m-d');
        }

        $this->model-create($store);

        if ($request->filled('continue')) {
            return redirect()->route('admin.historyIndex');
        }

        return redirect()->route('admin.historyIndex');
    }

    public function update(HistoryRequest $request, History $history) {
        $update = $request->validated();

        if ($request->hasFile('image')) {
            $fileName = $request->file('image')->getClientOriginalName();
            $path = $request->file('image')->storeAs('images', $fileName, 'public');
            $update['image'] = $path;
        }

        if ($request->filled('date')) {
            $date = Carbon::parse($request->input('date'));
            $update['date'] = $date->format('Y-m-d');
        }

        $history->update($update);

        return redirect()->route('admin.historyIndex');
    }
}

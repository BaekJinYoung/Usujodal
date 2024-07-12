<?php

namespace App\Http\Controllers;

use App\Http\Requests\YoutubeRequest;
use App\Models\Youtube;

class YoutubeController extends BaseController {

    public function __construct(Youtube $youtube) {
        parent::__construct($youtube);
    }

    public function store(YoutubeRequest $request) {
        $store = $request->validated();

        if ($request->hasFile('main_image')) {
            $fileName = $request->file('main_image')->getClientOriginalName();
            $path = $request->file('main_image')->storeAs('images', time() . '_' . $fileName, 'public');
            $store['main_image'] = $path;
        }

        $this->model->create($store);

        if ($request->filled('continue')) {
            return redirect()->route('admin.youtubeIndex');
        }

        return redirect()->route('admin.youtubeIndex');
    }

    public function update(YoutubeRequest $request, Youtube $youtube) {
        $update = $request->validated();

        if ($request->hasFile('main_image')) {
            $fileName = $request->file('main_image')->getClientOriginalName();
            $path = $request->file('main_image')->storeAs('images', time() . '_' . $fileName, 'public');
            $update['main_image'] = $path;
        }

        $youtube->update($update);

        return redirect()->route('admin.youtubeIndex');
    }
}

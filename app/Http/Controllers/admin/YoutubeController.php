<?php

namespace App\Http\Controllers\admin;

use App\Http\Requests\YoutubeRequest;
use App\Models\Youtube;

class YoutubeController extends BaseController {

    public function __construct(Youtube $youtube) {
        parent::__construct($youtube);
    }

    public function store(YoutubeRequest $request) {
        $store = $request->validated();

        if ($request->hasFile('image')) {
            $fileName = $request->file('image')->getClientOriginalName();
            $path = $request->file('image')->storeAs('images', $fileName, 'public');
            $store['image'] = $path;
        }

        $isFeatured = $request->input('is_featured');
        $store['is_featured'] = $isFeatured;

        $this->model->create($store);

        if ($request->filled('continue')) {
            return redirect()->route('admin.youtubeIndex');
        }

        return redirect()->route('admin.youtubeIndex');
    }

    public function update(YoutubeRequest $request, Youtube $youtube) {
        $update = $request->validated();

        if ($request->hasFile('image')) {
            $fileName = $request->file('image')->getClientOriginalName();
            $path = $request->file('image')->storeAs('images', $fileName, 'public');
            $update['image'] = $path;
        }

        $youtube->update($update);

        return redirect()->route('admin.youtubeIndex');
    }
}

<?php

namespace App\Http\Controllers\admin;

use App\Http\Requests\YoutubeRequest;
use App\Models\Youtube;

class YoutubeController extends BaseController {

    public function __construct(Youtube $youtube) {
        parent::__construct($youtube);
        $this->setDefaultPerPage(8);
    }

    public function store(YoutubeRequest $request) {
        $store = $request->validated();

        if (isset($store['content'])) {
            $store['content'] = preg_replace('/^<p>(.*?)<\/p>$/s', '$1', $store['content']);
        }

        $isFeatured = $request->input('is_featured');
        $store['is_featured'] = $isFeatured;

        $this->model->create($store);

        if ($request->filled('continue')) {
            return redirect()->route('admin.youtubeCreate');
        }

        return redirect()->route('admin.youtubeIndex');
    }

    public function update(YoutubeRequest $request, Youtube $youtube) {
        $update = $request->validated();

        if (isset($update['content'])) {
            $update['content'] = preg_replace('/^<p>(.*?)<\/p>$/s', '$1', $update['content']);
        }

        $youtube->update($update);

        return redirect()->route('admin.youtubeIndex');
    }
}

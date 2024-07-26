<?php

namespace App\Http\Controllers\admin;

use App\Http\Requests\ShareRequest;
use App\Models\Share;

class ShareController extends BaseController {

    public function __construct(Share $share) {
        parent::__construct($share);
    }

    public function store(ShareRequest $request) {
        $store = $request->validated();

        if (isset($store['content'])) {
            $store['content'] = preg_replace('/^<p>(.*?)<\/p>$/s', '$1', $store['content']);
        }

        if ($request->hasFile('file')) {
            $fileName = $request->file('file')->getClientOriginalName();
            $filePath = $request->file('file')->storeAs('uploads', $fileName, 'public');
            $store['file_path'] = $filePath;
        }

        $isFeatured = $request->input('is_featured');
        $store['is_featured'] = $isFeatured;

        $this->model->create($store);

        if ($request->filled('continue')) {
            return redirect()->route('admin.shareCreate');
        }

        return redirect()->route('admin.shareIndex');
    }

    public function update(ShareRequest $request, Share $share) {
        $update = $request->validated();

        if (isset($update['content'])) {
            $update['content'] = preg_replace('/^<p>(.*?)<\/p>$/s', '$1', $update['content']);
        }

        if ($request->input('remove_image') == '1') {
            $share->file_path = null;
        } else if ($request->hasFile('file')) {
            $fileName = $request->file('file')->getClientOriginalName();
            $filePath = $request->file('file')->storeAs('uploads', $fileName, 'public');
            $update['file_path'] = $filePath;
        }

        $share->update($update);

        return redirect()->route('admin.shareIndex');
    }
}

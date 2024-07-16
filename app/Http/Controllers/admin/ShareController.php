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

        if ($request->hasFile('image')) {
            $fileName = $request->file('image')->getClientOriginalName();
            $path = $request->file('image')->storeAs('images', $fileName, 'public');
            $store['image'] = $path;
        }

        if ($request->hasFile('file')) {
            $fileName = $request->file('file')->getClientOriginalName();
            $filePath = $request->file('file')->storeAs('uploads', $fileName, 'public');
            $store['file_path'] = $filePath;
        } else {
            $store['file_path'] = null;
        }

        $isFeatured = $request->input('is_featured');
        $store['is_featured'] = $isFeatured;

        $this->model->create($store);

        if ($request->filled('continue')) {
            return redirect()->route('admin.shareIndex');
        }

        return redirect()->route('admin.shareIndex');
    }

    public function update(ShareRequest $request, Share $share) {
        $update = $request->validated();

        if ($request->hasFile('image')) {
            $fileName = $request->file('image')->getClientOriginalName();
            $path = $request->file('image')->storeAs('images', $fileName, 'public');
            $update['image'] = $path;
        }

        $share->update($update);

        return redirect()->route('admin.shareIndex');
    }
}

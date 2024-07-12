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

        $share->update($update);

        return redirect()->route('admin.shareIndex');
    }
}

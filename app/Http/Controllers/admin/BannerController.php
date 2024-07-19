<?php

namespace App\Http\Controllers\admin;

use App\Http\Requests\BannerRequest;
use App\Models\Banner;

class BannerController extends BaseController
{
    public function __construct(Banner $banner) {
        parent::__construct($banner);
    }

    public function store(BannerRequest $request)
    {
        $store = $request->validated();

        $fileName = $request->file('image')->getClientOriginalName();
        $path = $request->file('image')->storeAs('images', $fileName, 'public');
        $store['image'] = $path;

        $this->model->create($store);

        if ($request->filled('continue')) {
            return redirect()->route('admin.bannerCreate');
        }

        return redirect()->route('admin.bannerIndex');
    }

    public function edit($id, $incrementViews = false) {
        return parent::edit($id, false);
    }

    public function update(BannerRequest $request, Banner $banner) {
        $update = $request->validated();

        if ($request->hasFile('image')) {
            $fileName = $request->file('image')->getClientOriginalName();
            $path = $request->file('image')->storeAs('images', $fileName, 'public');
            $update['image'] = $path;
        }

        $banner->update($update);

        return redirect()->route('admin.bannerIndex');
    }
}

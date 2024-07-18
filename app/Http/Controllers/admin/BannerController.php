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

        $mobile_fileName = $request->file('mobile_image')->getClientOriginalName();
        $mobile_path = $request->file('mobile_image')->storeAs('images', $mobile_fileName, 'public');
        $store['mobile_image'] = $mobile_path;

        $this->model->create($store);

        if ($request->filled('continue')) {
            return redirect()->route('admin.bannerCreate');
        }

        return redirect()->route('admin.bannerIndex');
    }
}

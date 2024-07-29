<?php

namespace App\Http\Controllers\admin;

use App\Http\Requests\BannerRequest;
use App\Models\Banner;

class BannerController extends BaseController
{
    public function __construct(Banner $banner) {
        parent::__construct($banner);
        $this->setDefaultPerPage(10);
    }

    public function store(BannerRequest $request)
    {
        $store = $request->validated();

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $fileName = $file->getClientOriginalName();
            $fileType = $file->getMimeType();

            if (strpos($fileType, 'image/') === 0) {
                $path = $file->storeAs('images', $fileName, 'public');
                $store['image'] = $path;
            } elseif (strpos($fileType, 'video/') === 0) {
                $path = $file->storeAs('videos', $fileName, 'public');
                $store['image'] = $path;
            }
        }

        if ($request->hasFile('mobile_image')) {
            $file = $request->file('mobile_image');
            $fileName = $file->getClientOriginalName();
            $fileType = $file->getMimeType();

            if (strpos($fileType, 'image/') === 0) {
                $path = $file->storeAs('images', $fileName, 'public');
                $store['mobile_image'] = $path;
            } elseif (strpos($fileType, 'video/') === 0) {
                $path = $file->storeAs('videos', $fileName, 'public');
                $store['mobile_image'] = $path;
            }
        }

        $this->model->create($store);

        if ($request->filled('continue')) {
            return redirect()->route('admin.bannerCreate');
        }

        return redirect()->route('admin.bannerIndex');
    }

    public function update(BannerRequest $request, Banner $banner) {
        $update = $request->validated();

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $fileName = $file->getClientOriginalName();
            $fileType = $file->getMimeType();

            if (strpos($fileType, 'image/') === 0) {
                $path = $file->storeAs('images', $fileName, 'public');
                $update['image'] = $path;
            } elseif (strpos($fileType, 'video/') === 0) {
                $path = $file->storeAs('videos', $fileName, 'public');
                $update['image'] = $path;
            }
        }

        if ($request->hasFile('mobile_image')) {
            $file = $request->file('mobile_image');
            $fileName = $file->getClientOriginalName();
            $fileType = $file->getMimeType();

            if (strpos($fileType, 'image/') === 0) {
                $path = $file->storeAs('images', $fileName, 'public');
                $update['mobile_image'] = $path;
            } elseif (strpos($fileType, 'video/') === 0) {
                $path = $file->storeAs('videos', $fileName, 'public');
                $update['mobile_image'] = $path;
            }
        }

        $banner->update($update);

        return redirect()->route('admin.bannerIndex');
    }
}

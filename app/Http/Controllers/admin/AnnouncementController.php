<?php

namespace App\Http\Controllers\admin;

use App\Http\Requests\AnnouncementRequest;
use App\Models\Announcement;

class AnnouncementController extends BaseController {

    public function __construct(Announcement $announcement) {
        parent::__construct($announcement);
    }

    public function store(AnnouncementRequest $request) {
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
            return redirect()->route('admin.announcementCreate');
        }

        return redirect()->route('admin.announcementIndex');
    }

    public function Update(AnnouncementRequest $request, Announcement $announcement) {
        $update = $request->validated();

        if (isset($update['content'])) {
            $update['content'] = preg_replace('/^<p>(.*?)<\/p>$/s', '$1', $update['content']);
        }

        if ($request->hasFile('file')) {
            $fileName = $request->file('file')->getClientOriginalName();
            $filePath = $request->file('file')->storeAs('uploads', $fileName, 'public');
            $update['file_path'] = $filePath;
        }

        $announcement->update($update);

        return redirect()->route('admin.announcementIndex');
    }
}

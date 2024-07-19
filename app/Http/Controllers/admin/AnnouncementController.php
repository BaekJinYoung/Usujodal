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

        if ($request->hasFile('image')) {
            $fileName = $request->file('image')->getClientOriginalName();
            $path = $request->file('image')->storeAs('images', $fileName, 'public');
            $store['image'] = $path;
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
            return redirect()->route('admin.announcementIndex');
        }

        return redirect()->route('admin.announcementIndex');
    }

    public function Update(AnnouncementRequest $request, Announcement $announcement) {
        $update = $request->validated();

        if ($request->hasFile('image')) {
            $fileName = $request->file('image')->getClientOriginalName();
            $path = $request->file('image')->storeAs('images', $fileName, 'public');
            $update['image'] = $path;
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

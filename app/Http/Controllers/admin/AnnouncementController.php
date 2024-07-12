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

        $isFeatured = $request->input('is_featured');
        $store['is_featured'] = $isFeatured;

        $this->model->create($store);

        if ($request->filled('continue')) {
            return redirect()->route('admin.announcementIndex');
        }

        return redirect()->route('admin.announcementIndex');
    }

    public function Update(AnnouncementRequest $request, Announcement $announcement) {
        $validatedData = $request->validated();

        $announcement->update($validatedData);

        return redirect()->route('admin.announcementIndex');
    }
}

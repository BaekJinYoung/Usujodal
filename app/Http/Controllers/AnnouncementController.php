<?php

namespace App\Http\Controllers;

use App\Http\Requests\AnnouncementRequest;
use App\Models\Announcement;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    public function __construct(Announcement $announcement)
    {
        $this->Announcement = $announcement;
    }

    public function index(Request $request) {
        $query = $this->Announcement->query();
        $search = $request->input('search', '');

        if (!empty($search)) {
            $query->where('title', 'like', '%' . $search . '%');
        }

        $perPage = $request->query('perPage', 10);
        $announcements = $query->latest()->paginate($perPage);

        return view('admin.announcementIndex', compact('announcements', 'perPage', 'search'));
    }

    public function create()
    {
        return view('admin.announcementCreate');
    }

    public function store(AnnouncementRequest $request)
    {
        $store = $request->validated();

        $isFeatured = $request->input('is_featured');
        $store['is_featured'] = $isFeatured;

        $this->Announcement->create($store);

        if ($request->filled('continue')) {
            return redirect()->route('admin.announcementIndex');
        }

        return redirect()->route('admin.announcementIndex');
    }
}

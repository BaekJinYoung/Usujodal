<?php

namespace App\Http\Controllers;

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
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\YoutubeRequest;
use App\Models\Youtube;
use Illuminate\Http\Request;

class YoutubeController extends Controller
{
    public function __construct(Youtube $youtube)
    {
        $this->Youtube = $youtube;
    }

    public function index(Request $request) {
        $query = $this->Youtube->query();
        $search = $request->input('search', '');

        if (!empty($search)) {
            $query->where('title', 'like', '%' . $search . '%');
        }

        $perPage = $request->query('perPage', 10);
        $youtubes = $query->latest()->paginate($perPage);

        return view('admin.youtubeIndex', compact('youtubes', 'perPage', 'search'));
    }

    public function create()
    {
        return view('admin.youtubeCreate');
    }

    public function store(YoutubeRequest $request)
    {
        $store = $request->validated();

        if ($request->hasFile('image')) {
            $fileName = $request->file('image')->getClientOriginalName();
            $path = $request->file('image')->storeAs('images', time() . '_' . $fileName, 'public');
            $store['image'] = $path;
        }

        $this->Youtube->create($store);

        if ($request->filled('continue')) {
            return redirect()->route('admin.youtubeIndex');
        }

        return redirect()->route('admin.youtubeIndex');
    }

    public function edit($id)
    {
        $youtube = $this->Youtube->find($id);

        return view('admin.youtubeEdit', compact('youtube'));
    }

    public function update(YoutubeRequest $request, Youtube $youtube)
    {
        $update = $request->validated();

        $youtube->update($update);

        return redirect()->route('admin.youtubeIndex');
    }

    public function delete(Youtube $youtube)
    {
        $youtube->delete();
        return redirect()->route('admin.youtubeIndex');
    }
}

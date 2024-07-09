<?php

namespace App\Http\Controllers;

use App\Http\Requests\ShareRequest;
use App\Models\Share;
use Illuminate\Http\Request;

class ShareController extends Controller
{
    public function __construct(Share $share)
    {
        $this->Share = $share;
    }

    public function index(Request $request) {
        $query = $this->Share->query();
        $search = $request->input('search', '');

        if (!empty($search)) {
            $query->where('title', 'like', '%' . $search . '%');
        }

        $perPage = $request->query('perPage', 10);
        $shares = $query->latest()->paginate($perPage);

        return view('admin.shareIndex', compact('shares', 'perPage', 'search'));
    }

    public function create()
    {
        return view('admin.shareCreate');
    }

    public function store(ShareRequest $request)
    {
        $store = $request->validated();

        $isFeatured = $request->input('is_featured');
        $store['is_featured'] = $isFeatured;

        $this->Share->create($store);

        if ($request->filled('continue')) {
            return redirect()->route('admin.shareIndex');
        }

        return redirect()->route('admin.shareIndex');
    }

    public function edit($id)
    {
        $share = $this->Share->find($id);

        return view('admin.shareEdit', compact('share'));
    }

    public function update(ShareRequest $request, Share $share)
    {
        $update = $request->validated();

        $share->update($update);

        return redirect()->route('admin.shareIndex');
    }

    public function delete(Share $share)
    {
        $share->delete();
        return redirect()->route('admin.shareIndex');
    }
}

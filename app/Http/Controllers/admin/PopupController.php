<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PopupRequest;
use App\Models\Popup;

class PopupController extends BaseController
{
    public function __construct(Popup $popup) {
        parent::__construct($popup);
    }

    public function store(PopupRequest $request)
    {
        $store = $request->validated();

        $fileName = $request->file('image')->getClientOriginalName();
        $path = $request->file('image')->storeAs('images', $fileName, 'public');
        $store['image'] = $path;

        $this->model->create($store);

        if ($request->filled('continue')) {
            return redirect()->route('admin.popupCreate');
        }

        return redirect()->route('admin.popupIndex');
    }

    public function update(PopupRequest $request, Popup $popup) {
        $update = $request->validated();

        if ($request->input('remove_image') == '1') {
            $popup->image = null;
        } else if ($request->hasFile('image')) {
            $fileName = $request->file('image')->getClientOriginalName();
            $path = $request->file('image')->storeAs('images', $fileName, 'public');
            $update['image'] = $path;
        }

        $popup->update($update);

        return redirect()->route('admin.popupIndex');
    }
}

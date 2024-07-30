<?php

namespace App\Http\Controllers\admin;

use App\Http\Requests\ConsultantRequest;
use App\Models\Consultant;

class ConsultantController extends BaseController {
    public function __construct(Consultant $consultant) {
        parent::__construct($consultant);
        $this->setDefaultPerPage(0);
    }

    public function store(ConsultantRequest $request) {
        $store = $request->validated();

        if ($request->hasFile('image')) {
            $fileName = $request->file('image')->getClientOriginalName();
            $path = $request->file('image')->storeAs('images', $fileName, 'public');
            $store['image'] = $path;
        }

        $this->model->create($store);

        if ($request->filled('continue')) {
            return redirect()->route('admin.consultantCreate');
        }

        return redirect()->route('admin.consultantIndex');
    }

    public function update(ConsultantRequest $request, Consultant $consultant) {
        $update = $request->validated();

        if ($request->input('remove_image') == '1') {
            $consultant->image = null;
        } else if ($request->hasFile('image')) {
            $fileName = $request->file('image')->getClientOriginalName();
            $path = $request->file('image')->storeAs('images', $fileName, 'public');
            $update['image'] = $path;
        }

        $consultant->update($update);

        return redirect()->route('admin.consultantIndex');
    }
}

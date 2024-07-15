<?php

namespace App\Http\Controllers\admin;

use App\Http\Requests\ConsultantRequest;
use App\Models\Consultant;

class ConsultantController extends BaseController {
    public function __construct(Consultant $consultant) {
        parent::__construct($consultant);
    }

    public function store(ConsultantRequest $request) {
        $store = $request->validated();

        $fileName = $request->file('main_image')->getClientOriginalName();
        $path = $request->file('main_image')->storeAs('images', time() . '_' . $fileName, 'public');
        $store['main_image'] = $path;

        $this->model->create($store);

        return redirect()->route('admin.consultantIndex');
    }

    public function update(ConsultantRequest $request, Consultant $consultant) {
        $update = $request->validated();

        if ($request->hasFile('main_image')) {
            $fileName = $request->file('main_image')->getClientOriginalName();
            $path = $request->file('main_image')->storeAs('images', time() . '_' . $fileName, 'public');
            $update['main_image'] = $path;
        }

        $consultant->update($consultant);

        return redirect()->route('admin.consultantIndex');
    }
}

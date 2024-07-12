<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ConsultantRequest;
use App\Models\Consultant;

class ConsultantController extends Controller {
    public function __construct(Consultant $consultant) {
        $this->Consultant = $consultant;
    }

    public function index() {
        $consultant = $this->Consultant->query();
        return view('admin.consultantIndex', compact('consultant'));
    }

    public function store(ConsultantRequest $request) {
        $store = $request->validated();

        $fileName = $request->file('main_image')->getClientOriginalName();
        $path = $request->file('main_image')->storeAs('images', time() . '_' . $fileName, 'public');
        $store['main_image'] = $path;

        $this->Consultant->create($store);

        return redirect()->route('admin.consultantIndex');
    }
}

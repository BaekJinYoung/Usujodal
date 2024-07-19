<?php

namespace App\Http\Controllers\admin;

use App\Http\Requests\CompanyRequest;
use App\Models\Company;

class CompanyController extends BaseController {

    public function __construct(Company $company) {
        parent::__construct($company);
    }

    public function store(CompanyRequest $request) {
        $store = $request->validated();

        if ($request->hasFile('image')) {
            $fileName = $request->file('image')->getClientOriginalName();
            $path = $request->file('image')->storeAs('images', $fileName, 'public');
            $store['image'] = $path;
        } else {
            $store['image'] = null;
        }

        if ($request->hasFile('file')) {
            $fileName = $request->file('file')->getClientOriginalName();
            $filePath = $request->file('file')->storeAs('uploads', $fileName, 'public');
            $store['file_path'] = $filePath;
        } else {
            $store['file_path'] = null;
        }

        $this->model->create($store);

        if ($request->filled('continue')) {
            return redirect()->route('admin.companyCreate');
        }

        return redirect()->route('admin.companyIndex');
    }

    public function update(CompanyRequest $request, Company $company) {
        $update = $request->validated();

        if ($request->hasFile('image')) {
            $fileName = $request->file('image')->getClientOriginalName();
            $path = $request->file('image')->storeAs('images', $fileName, 'public');
            $update['image'] = $path;
        } else {
            $update['image'] = null;
        }

        $company->update($update);

        return redirect()->route('admin.companyIndex');
    }
}

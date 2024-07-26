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

        if (isset($store['content'])) {
            $store['content'] = preg_replace('/^<p>(.*?)<\/p>$/s', '$1', $store['content']);
        }

        if ($request->hasFile('image')) {
            $fileName = $request->file('image')->getClientOriginalName();
            $path = $request->file('image')->storeAs('images', $fileName, 'public');
            $store['image'] = $path;
        }

        if ($request->hasFile('file')) {
            $fileName = $request->file('file')->getClientOriginalName();
            $filePath = $request->file('file')->storeAs('uploads', $fileName, 'public');
            $store['file_path'] = $filePath;
        }

        $this->model->create($store);

        if ($request->filled('continue')) {
            return redirect()->route('admin.companyCreate');
        }

        return redirect()->route('admin.companyIndex');
    }

    public function update(CompanyRequest $request, Company $company) {
        $update = $request->validated();

        if (isset($update['content'])) {
            $update['content'] = preg_replace('/^<p>(.*?)<\/p>$/s', '$1', $update['content']);
        }

        if ($request->input('remove_image') == '1') {
            $company->image = null;
        } else if ($request->hasFile('image')) {
            $fileName = $request->file('image')->getClientOriginalName();
            $path = $request->file('image')->storeAs('images', $fileName, 'public');
            $update['image'] = $path;
        }

        if ($request->input('remove_file') == '1') {
            $company->file_path = null;
        } else if ($request->hasFile('file')) {
            $fileName = $request->file('file')->getClientOriginalName();
            $filePath = $request->file('file')->storeAs('uploads', $fileName, 'public');
            $update['file_path'] = $filePath;
        }

        $company->update($update);

        return redirect()->route('admin.companyIndex');
    }
}

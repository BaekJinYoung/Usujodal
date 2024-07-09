<?php

namespace App\Http\Controllers;

use App\Http\Requests\CompanyRequest;
use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function __construct(Company $company){
        $this->Company = $company;
    }

    public function index(Request $request) {
        $query = $this->Company->query();
        $search = $request->input('search', '');

        if (!empty($search)) {
            $query->where('title', 'like', '%' . $search . '%');
        }

        $perPage = $request->query('perPage', 8);
        $companies = $query->latest()->paginate($perPage);

        return view('admin.companyIndex', compact('companies', 'perPage', 'search'));
    }

    public function create(){
        return view('admin.companyCreate');
    }

    public function store(CompanyRequest $request){
        $store = $request->validated();

        if ($request->hasFile('image')) {
            $fileName = $request->file('image')->getClientOriginalName();
            $path = $request->file('image')->storeAs('images', time() . '_' . $fileName, 'public');
            $store['main_image'] = $path;
        } else {
            $store['main_image'] = null;
        }

        $this->Company->create($store);

        if ($request->filled('continue')) {
            return redirect()->route('admin.companyCreate');
        }

        return redirect()->route('admin.companyIndex');
    }

    public function edit($id){
        $company = $this->Company->find($id);

        return view('admin.companyEdit', compact('company'));
    }

    public function update(CompanyRequest $request, Company $company){
        $update = $request->validated();

        $company->update($update);

        return redirect()->route('admin.companyIndex');
    }
}

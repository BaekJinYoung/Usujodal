<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function __construct(Company $company)
    {
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

    public function create()
    {
        return view('admin.companyCreate');
    }
}

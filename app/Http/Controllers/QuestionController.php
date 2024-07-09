<?php

namespace App\Http\Controllers;

use App\Http\Requests\QuestionRequest;
use App\Models\Question;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function __construct(Question $announcement)
    {
        $this->Question = $announcement;
    }

    public function index(Request $request) {
        $query = $this->Question->query();
        $search = $request->input('search', '');

        if (!empty($search)) {
            $query->where('title', 'like', '%' . $search . '%');
        }

        $perPage = $request->query('perPage', 10);
        $questions = $query->latest()->paginate($perPage);

        return view('admin.questionIndex', compact('questions', 'perPage', 'search'));
    }

    public function create()
    {
        return view('admin.questionCreate');
    }

    public function store(QuestionRequest $request)
    {
        $store = $request->validated();

        $this->Question->create($store);

        if ($request->filled('continue')) {
            return redirect()->route('admin.questionIndex');
        }

        return redirect()->route('admin.questionIndex');
    }
}

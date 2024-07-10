<?php

namespace App\Http\Controllers;

use App\Http\Requests\QuestionRequest;
use App\Models\Question;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function __construct(Question $question) {
        $this->Question = $question;
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

    public function create() {
        return view('admin.questionCreate');
    }

    public function store(QuestionRequest $request) {
        $store = $request->validated();

        $this->Question->create($store);

        if ($request->filled('continue')) {
            return redirect()->route('admin.questionIndex');
        }

        return redirect()->route('admin.questionIndex');
    }

    public function edit($id) {
        $question = $this->Question->find($id);

        return view('admin.questionEdit', compact('question'));
    }

    public function update(QuestionRequest $request, Question $question) {
        $update = $request->validated();

        $question->update($update);

        return redirect()->route('admin.questionIndex');
    }

    public function delete(Question $question) {
        $question->delete();
        return redirect()->route('admin.questionIndex');
    }
}

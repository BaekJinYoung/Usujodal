<?php

namespace App\Http\Controllers\admin;

use App\Http\Requests\QuestionRequest;
use App\Models\Question;

class QuestionController extends BaseController {
    public function __construct(Question $question) {
        parent::__construct($question);
    }

    public function store(QuestionRequest $request) {
        $store = $request->validated();

        $this->model->create($store);

        if ($request->filled('continue')) {
            return redirect()->route('admin.questionCreate');
        }

        return redirect()->route('admin.questionIndex');
    }

    public function update(QuestionRequest $request, Question $question) {
        $update = $request->validated();

        $question->update($update);

        return redirect()->route('admin.questionIndex');
    }
}

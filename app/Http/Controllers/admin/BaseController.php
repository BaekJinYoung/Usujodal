<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BaseController extends Controller {
    protected $model;

    public function __construct($model) {
        $this->model = $model;
    }

    public function index(Request $request) {
        $query = $this->model->query();
        $search = $request->input('search', '');

        if (!empty($search)) {
            $query->where('title', 'like', '%' . $search . '%');
        }

        $perPage = $request->query('perPage', 10);
        $items = $query->latest()->paginate($perPage);

        return view($this->getViewName('index'), compact('items', 'perPage', 'search'));
    }

    public function create() {
        return view($this->getViewName('create'));
    }

    public function edit($id) {
        $item = $this->model->find($id);

        return view($this->getViewName('edit'), compact('item'));
    }

    public function delete($item) {
        $item = $this->model->findOrFail($item);
        $item->delete();

        return redirect()->route($this->getRouteName('index'));
    }

    protected function getViewName($view) {
        return 'admin.' . strtolower(class_basename($this->model)) . ucfirst($view);
    }

    protected function getRouteName($route) {
        return 'admin.' . strtolower(class_basename($this->model)) . ucfirst($route);
    }
}

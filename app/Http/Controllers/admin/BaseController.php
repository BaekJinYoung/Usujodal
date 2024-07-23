<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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

        if (!$item) {
            return redirect()->route($this->getRouteName('index'))
            ->with('error', '해당 게시물을 찾을 수 없습니다.');
        }

        if (array_key_exists('image', $item->toArray())) {
            $fileExists = isset($item->image) && Storage::exists('public/' . $item->image);

            if ($fileExists) {
                $item['image_name'] = pathinfo($item->image, PATHINFO_FILENAME) . '.' . pathinfo($item->image, PATHINFO_EXTENSION);
                $item['image'] = asset('storage/' . $item->image);
            } else {
                $item['image_name'] = null;
                $item['image'] = null;
            }
        } else {
            unset($item['image_name']);
            unset($item['image']);
        }

        if (array_key_exists('file_path', $item->toArray())) {
            $fileExists = isset($item->file_path) && Storage::exists('public/' . $item->file_path);

            if ($fileExists) {
                $item['file_name'] = pathinfo($item->file_path, PATHINFO_FILENAME) . '.' . pathinfo($item->file_path, PATHINFO_EXTENSION);
                $item['file_path'] = asset('storage/' . $item->file_path);
            } else {
                $item['file_name'] = null;
                $item['file_path'] = null;
            }
        } else {
            unset($item['file_name']);
            unset($item['file_path']);
        }

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

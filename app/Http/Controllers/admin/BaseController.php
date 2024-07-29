<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BaseController extends Controller {
    protected $model;

    protected $defaultPerPage = 10;

    public function __construct($model) {
        $this->model = $model;
    }

    public function index(Request $request) {
        $query = $this->model->query();
        $search = $request->input('search', '');

        if (!empty($search)) {
            $query->where('title', 'like', '%' . $search . '%');
        }

        $perPage = (int) $request->query('perPage', $this->defaultPerPage);
        if ($perPage <= 0) {
            $perPage = $this->defaultPerPage;
        }

        $items = $query->orderBy('id', 'desc')->paginate($perPage);

        $items->getCollection()->transform(function ($item) {
            $item->is_featured = $item->is_featured ? 'Y' : 'N';
            return $item;
        });

        return view($this->getViewName('index'), compact('items', 'perPage', 'search'));
    }

    public function setDefaultPerPage($value) {
        $this->defaultPerPage = $value;
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

        $attributes = [
            'image' => 'image_name',
            'mobile_image' => 'mobile_image_name',
            'file_path' => 'file_name'
        ];

        foreach ($attributes as $attribute => $fileNameAttribute) {
            $item = $this->addFileInformation($item, $attribute, $fileNameAttribute);
        }

        return view($this->getViewName('edit'), compact('item'));
    }

    private function addFileInformation($item, $attribute, $fileNameAttribute)
    {
        if (array_key_exists($attribute, $item->toArray())) {
            $fileExists = isset($item->{$attribute}) && Storage::exists('public/' . $item->{$attribute});

            if ($fileExists) {
                $item[$fileNameAttribute] = pathinfo($item->{$attribute}, PATHINFO_FILENAME) . '.' . pathinfo($item->{$attribute}, PATHINFO_EXTENSION);
                $item[$attribute] = asset('storage/' . $item->{$attribute});
            } else {
                $item[$fileNameAttribute] = null;
                $item[$attribute] = null;
            }
        } else {
            unset($item[$fileNameAttribute]);
            unset($item[$attribute]);
        }

        return $item;
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

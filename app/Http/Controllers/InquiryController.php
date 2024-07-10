<?php

namespace App\Http\Controllers;

use App\Http\Requests\InquiryRequest;
use App\Models\Inquiry;
use App\Models\InquiryFile;
use Illuminate\Http\Request;

class InquiryController extends Controller
{
    public function __construct(Inquiry $inquiry) {
        $this->Inquiry = $inquiry;
    }

    public function store(InquiryRequest $request) {
        $inquiry = $request->validated();

        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $filePath = $file->store('inquiries', 'public');
                InquiryFile::create([
                    'inquiry_id' => $inquiry->id,
                    'file_path' => $filePath,
                ]);
            }
        }

        $this->Inquiry->create($inquiry);

        return response()->json($inquiry, 201);
    }
}

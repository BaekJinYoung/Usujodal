<?php

namespace App\Http\Controllers\admin;

use App\Http\Requests\InquiryRequest;
use App\Http\Resources\ApiResponse;
use App\Models\Inquiry;

class InquiryController extends BaseController {
    public function __construct(Inquiry $inquiry) {
        parent::__construct($inquiry);
    }

    public function store(InquiryRequest $request) {
        $validatedData = $request->validated();

        $inquiry = Inquiry::create($validatedData);

        return ApiResponse::success($inquiry);
    }
}

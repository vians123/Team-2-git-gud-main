<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Http\Controllers\Controller;
use App\Services\API\InquiryService;
use App\Http\Resources\InquiryResource;
use App\Http\Requests\API\Inquiry\CreateInquiryRequest;

/**
 * @group Inquiry Management
 */
class InquiryController extends Controller
{
    /** @var \App\Services\API\nquiryService */
    protected $inquiryService;

    public function __construct(InquiryService $inquiryService)
    {
        parent::__construct();
        $this->inquiryService = $inquiryService;
    }

    /**
     * Create New Inquiry
     *
     * Stores the Inquiry in database and notifies admin via email.
     *
     * @param \App\Http\Requests\API\Inquiry\CreateInquiryRequest $request
     * @return Illuminate\Http\Response
     */
    public function create(CreateInquiryRequest $request)
    {
        $request->validated();

        try {
            $formData = [
                'fullname' => $request->getFullname(),
                'email' => $request->getEmail(),
                'content' => $request->getInquiryContent(),
            ];
            $inquiry = $this->inquiryService->create($formData);
            $this->response['data'] = new InquiryResource($inquiry);
        } catch (Exception $e) { // @codeCoverageIgnoreStart
            $this->response = [
                'error' => $e->getMessage(),
                'code' => 500,
            ];
        } // @codeCoverageIgnoreEnd

        return response()->json($this->response, $this->response['code']);
    }
}

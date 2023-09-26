<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\RequestManagementRequest;
use App\Http\Requests\InstallerRequests\UpdateStatusRequest;
use App\Models\Expert;
use App\Models\Request as InstallerRequest;
use App\Models\RequestStatusMetrics;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class RequestManagementController extends AdminController
{
    /**
     * Redirect back to login if user is unauthenticated
     *
     * RequestController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(RequestManagementRequest $request, bool $deleted = false)
    {
        $data = InstallerRequest::with('installer');

        $data = $data->get();

        $totalRequests = InstallerRequest::getTotalRequestCount();
        $newRequests = InstallerRequest::getTotalNewRequestCount();
        $contactedRequests = InstallerRequest::getTotalContactedRequestCount();

        return Inertia::render('Admin/RequestManagement/Index', [
            'data' => $data,
            'numRequests' => $totalRequests,
            'numNew' => $newRequests,
            'numContacted' => $contactedRequests,
        ]);
    }

    /**
     * Admin page to list the request detail based on requestID
     *
     * @param $id
     * @return \Inertia\Response
     */
    public function view($requestId)
    {
        $request = InstallerRequest::whereId($requestId)->firstOrFail();

        return Inertia::render('Admin/RequestManagement/Detail', [
            'request' => $request,
        ]);
    }

    /**
     * Method to update request status
     *
     * @param $requestId
     * @param  Request  $request
     * @return bool|\Illuminate\Http\RedirectResponse
     */
    public function updateRequestStatus($requestId, UpdateStatusRequest $request)
    {

        $shedSolutionRequest = InstallerRequest::whereId($requestId)->firstOrFail();
        $currentStatus = $shedSolutionRequest->request_status;

        $valid = $request->validated();
        switch($shedSolutionRequest) {
            case $shedSolutionRequest->request_status == InstallerRequest::NEW_REQUEST:
                if ($valid['status'] == InstallerRequest::CONTACTED) { // 1
                    $shedSolutionRequest->request_status = InstallerRequest::CONTACTED;
                    $shedSolutionRequest->save();
                } elseif ($valid['status'] == InstallerRequest::NOT_CONVERTED) { // 4
                    $shedSolutionRequest->request_status = InstallerRequest::NOT_CONVERTED;
                    $shedSolutionRequest->save();
                } else {
                    return false;
                }

                break;
            case $shedSolutionRequest->request_status == InstallerRequest::CONTACTED:
                if ($valid['status'] == InstallerRequest::CONVERTED) { // 2
                    $shedSolutionRequest->request_status = InstallerRequest::CONVERTED;
                    $shedSolutionRequest->save();
                } elseif ($valid['status'] == InstallerRequest::NOT_CONVERTED) { // 4
                    $shedSolutionRequest->request_status = InstallerRequest::NOT_CONVERTED;
                    $shedSolutionRequest->save();
                } else {
                    return false;
                }

                break;
            case $shedSolutionRequest->request_status == InstallerRequest::CONVERTED:
                if ($valid['status'] == InstallerRequest::PROJECT_FINISHED) { // 3
                    $shedSolutionRequest->request_status = InstallerRequest::PROJECT_FINISHED;
                    $shedSolutionRequest->save();
                } elseif ($valid['status'] == InstallerRequest::PROJECT_FAILED) { // 5
                    $shedSolutionRequest->request_status = InstallerRequest::PROJECT_FAILED;
                    $shedSolutionRequest->save();
                } else {
                    return false;
                }

                break;
            case $shedSolutionRequest->request_status == InstallerRequest::NOT_CONVERTED:
                if ($valid['status'] == InstallerRequest::PROJECT_FAILED) { // 5
                    $shedSolutionRequest->request_status = InstallerRequest::PROJECT_FAILED;
                    $shedSolutionRequest->save();
                } else {
                    return false;
                }

                break;
        }

        if ($shedSolutionRequest->wasChanged()) {
            RequestStatusMetrics::logRequestStatusChange(
                $shedSolutionRequest->id,
                $shedSolutionRequest->user_id,
                $currentStatus,
                $shedSolutionRequest->request_status
            );
        }

        return redirect()->back();
    }
}

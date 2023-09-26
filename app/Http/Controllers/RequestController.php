<?php

namespace App\Http\Controllers;

use App\Http\Requests\InstallerRequests\UpdateStatusRequest;
use App\Models\Expert;
use App\Models\Request as InstallerRequest;
use App\Models\RequestStatusMetrics;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class RequestController extends Controller
{
    /**
     * Reirect back to login if user is unauthenticated
     *
     * RequestController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Page to list all requests
     *
     * @param  Request  $request
     * @return \Inertia\Response
     */
    public function index(Request $request)
    {
        $expert = Expert::where('user_id', Auth::user()->id)->firstOrFail();

        $requests = InstallerRequest::whereHas('installer.user', function($query) {
            $query->whereNull('deleted_at');
        })
            ->where('installer_id', $expert->id)
            ->orderByDesc('created_at')
            ->get();

        $newRequestCount = InstallerRequest::getNewRequestCount($expert->id);
        $readRequestCount = InstallerRequest::getReadRequestCount($expert->id);

        return Inertia::render('Requests/ListRequests', [
            'requests' => $requests,
            'newRequestCount' => $newRequestCount,
            'readRequestCount' => $readRequestCount,
        ]);
    }

    /**
     * Page to show request detail
     *
     * @param $requestId
     * @return \Inertia\Response
     */
    public function projectRequest($requestId)
    {
        $request = InstallerRequest::whereId($requestId)->firstOrFail();
        $p = $request->shed_solution ?? [];

        return Inertia::render('Requests/RequestDetail', ['request' => $request, 'project' => $p]);
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

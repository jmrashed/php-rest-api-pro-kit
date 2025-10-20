<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Response;
use App\Models\LeaveRequest;

class LeaveRequestController extends Controller
{
    public function index()
    {
        $perPage = $this->request->get('per_page', 10);
        $page = $this->request->get('page', 1);
        $leaveRequests = LeaveRequest::paginate($perPage, $page);
        return Response::json($leaveRequests);
    }

    public function show($id)
    {
        $leaveRequest = LeaveRequest::find($id);
        if (!$leaveRequest) {
            return Response::json(['error' => 'Leave Request not found'], 404);
        }
        return Response::json($leaveRequest);
    }

    public function store()
    {
        $data = $this->request->json();
        $leaveRequest = new LeaveRequest();
        $leaveRequest->employee_id = $data['employee_id'] ?? null;
        $leaveRequest->leave_type_id = $data['leave_type_id'] ?? null;
        $leaveRequest->start_date = $data['start_date'] ?? null;
        $leaveRequest->end_date = $data['end_date'] ?? null;
        $leaveRequest->reason = $data['reason'] ?? null;
        $leaveRequest->status = $data['status'] ?? 'pending';

        if ($leaveRequest->save()) {
            return Response::json(['message' => 'Leave Request created successfully', 'leave_request' => $leaveRequest], 201);
        }
        return Response::json(['error' => 'Failed to create leave request'], 500);
    }

    public function update($id)
    {
        $leaveRequest = LeaveRequest::find($id);
        if (!$leaveRequest) {
            return Response::json(['error' => 'Leave Request not found'], 404);
        }

        $data = $this->request->json();
        $leaveRequest->employee_id = $data['employee_id'] ?? $leaveRequest->employee_id;
        $leaveRequest->leave_type_id = $data['leave_type_id'] ?? $leaveRequest->leave_type_id;
        $leaveRequest->start_date = $data['start_date'] ?? $leaveRequest->start_date;
        $leaveRequest->end_date = $data['end_date'] ?? $leaveRequest->end_date;
        $leaveRequest->reason = $data['reason'] ?? $leaveRequest->reason;
        $leaveRequest->status = $data['status'] ?? $leaveRequest->status;

        if ($leaveRequest->save()) {
            return Response::json(['message' => 'Leave Request updated successfully', 'leave_request' => $leaveRequest]);
        }
        return Response::json(['error' => 'Failed to update leave request'], 500);
    }

    public function destroy($id)
    {
        $leaveRequest = LeaveRequest::find($id);
        if (!$leaveRequest) {
            return Response::json(['error' => 'Leave Request not found'], 404);
        }

        if ($leaveRequest->delete()) {
            return Response::json(['message' => 'Leave Request deleted successfully']);
        }
        return Response::json(['error' => 'Failed to delete leave request'], 500);
    }
}
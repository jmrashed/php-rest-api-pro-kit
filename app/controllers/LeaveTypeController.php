<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Response;
use App\Models\LeaveType;

class LeaveTypeController extends Controller
{
    public function index()
    {
        $perPage = $this->request->get('per_page', 10);
        $page = $this->request->get('page', 1);
        $leaveTypes = LeaveType::paginate($perPage, $page);
        return Response::json($leaveTypes);
    }

    public function show($id)
    {
        $leaveType = LeaveType::find($id);
        if (!$leaveType) {
            return Response::json(['error' => 'Leave Type not found'], 404);
        }
        return Response::json($leaveType);
    }

    public function store()
    {
        $data = $this->request->json();
        $leaveType = new LeaveType();
        $leaveType->name = $data['name'] ?? null;
        $leaveType->description = $data['description'] ?? null;
        $leaveType->max_days = $data['max_days'] ?? null;

        if ($leaveType->save()) {
            return Response::json(['message' => 'Leave Type created successfully', 'leave_type' => $leaveType], 201);
        }
        return Response::json(['error' => 'Failed to create leave type'], 500);
    }

    public function update($id)
    {
        $leaveType = LeaveType::find($id);
        if (!$leaveType) {
            return Response::json(['error' => 'Leave Type not found'], 404);
        }

        $data = $this->request->json();
        $leaveType->name = $data['name'] ?? $leaveType->name;
        $leaveType->description = $data['description'] ?? $leaveType->description;
        $leaveType->max_days = $data['max_days'] ?? $leaveType->max_days;

        if ($leaveType->save()) {
            return Response::json(['message' => 'Leave Type updated successfully', 'leave_type' => $leaveType]);
        }
        return Response::json(['error' => 'Failed to update leave type'], 500);
    }

    public function destroy($id)
    {
        $leaveType = LeaveType::find($id);
        if (!$leaveType) {
            return Response::json(['error' => 'Leave Type not found'], 404);
        }

        if ($leaveType->delete()) {
            return Response::json(['message' => 'Leave Type deleted successfully']);
        }
        return Response::json(['error' => 'Failed to delete leave type'], 500);
    }
}
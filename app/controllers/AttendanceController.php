<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Response;
use App\Models\Attendance;

class AttendanceController extends Controller
{
    public function index()
    {
        $perPage = $this->request->get('per_page', 10);
        $page = $this->request->get('page', 1);
        $attendanceRecords = Attendance::paginate($perPage, $page);
        return Response::success('Attendance records retrieved successfully', $attendanceRecords);
    }

    public function show($id)
    {
        $attendance = Attendance::find($id);
        if (!$attendance) {
            return Response::error('Attendance record not found', 404);
        }
        return Response::success('Attendance record retrieved successfully', $attendance);
    }

    public function store()
    {
        $data = $this->request->json();
        $attendance = new Attendance();
        $attendance->employee_id = $data['employee_id'] ?? null;
        $attendance->date = $data['date'] ?? null;
        $attendance->check_in_time = $data['check_in_time'] ?? null;
        $attendance->check_out_time = $data['check_out_time'] ?? null;
        $attendance->status = $data['status'] ?? 'present';

        if ($attendance->save()) {
            return Response::success('Attendance record created successfully', ['attendance' => $attendance], 201);
        }
        return Response::error('Failed to create attendance record', 500);
    }

    public function update($id)
    {
        $attendance = Attendance::find($id);
        if (!$attendance) {
            return Response::error('Attendance record not found', 404);
        }

        $data = $this->request->json();
        $attendance->employee_id = $data['employee_id'] ?? $attendance->employee_id;
        $attendance->date = $data['date'] ?? $attendance->date;
        $attendance->check_in_time = $data['check_in_time'] ?? $attendance->check_in_time;
        $attendance->check_out_time = $data['check_out_time'] ?? $attendance->check_out_time;
        $attendance->status = $data['status'] ?? $attendance->status;

        if ($attendance->save()) {
            return Response::success('Attendance record updated successfully', ['attendance' => $attendance]);
        }
        return Response::error('Failed to update attendance record', 500);
    }

    public function destroy($id)
    {
        $attendance = Attendance::find($id);
        if (!$attendance) {
            return Response::error('Attendance record not found', 404);
        }

        if ($attendance->delete()) {
            return Response::success('Attendance record deleted successfully');
        }
        return Response::error('Failed to delete attendance record', 500);
    }
}
<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Response;
use App\Models\EmployeeProfile;

class EmployeeProfileController extends Controller
{
    public function index()
    {
        $perPage = $this->request->get('per_page', 10);
        $page = $this->request->get('page', 1);
        $employeeProfiles = EmployeeProfile::paginate($perPage, $page);
        return Response::success('Employee Profiles retrieved successfully', $employeeProfiles);
    }

    public function show($id)
    {
        $employeeProfile = EmployeeProfile::find($id);
        if (!$employeeProfile) {
            return Response::error('Employee Profile not found', 404);
        }
        return Response::success('Employee Profile retrieved successfully', $employeeProfile);
    }

    public function store()
    {
        $data = $this->request->json();
        $employeeProfile = new EmployeeProfile();
        $employeeProfile->user_id = $data['user_id'] ?? null;
        $employeeProfile->department_id = $data['department_id'] ?? null;
        $employeeProfile->position = $data['position'] ?? null;
        $employeeProfile->hire_date = $data['hire_date'] ?? null;
        $employeeProfile->salary = $data['salary'] ?? null;

        if ($employeeProfile->save()) {
            return Response::success('Employee Profile created successfully', ['employee_profile' => $employeeProfile], 201);
        }
        return Response::error('Failed to create employee profile', 500);
    }

    public function update($id)
    {
        $employeeProfile = EmployeeProfile::find($id);
        if (!$employeeProfile) {
            return Response::error('Employee Profile not found', 404);
        }

        $data = $this->request->json();
        $employeeProfile->user_id = $data['user_id'] ?? $employeeProfile->user_id;
        $employeeProfile->department_id = $data['department_id'] ?? $employeeProfile->department_id;
        $employeeProfile->position = $data['position'] ?? $employeeProfile->position;
        $employeeProfile->hire_date = $data['hire_date'] ?? $employeeProfile->hire_date;
        $employeeProfile->salary = $data['salary'] ?? $employeeProfile->salary;

        if ($employeeProfile->save()) {
            return Response::success('Employee Profile updated successfully', ['employee_profile' => $employeeProfile]);
        }
        return Response::error('Failed to update employee profile', 500);
    }

    public function destroy($id)
    {
        $employeeProfile = EmployeeProfile::find($id);
        if (!$employeeProfile) {
            return Response::error('Employee Profile not found', 404);
        }

        if ($employeeProfile->delete()) {
            return Response::success('Employee Profile deleted successfully');
        }
        return Response::error('Failed to delete employee profile', 500);
    }
}
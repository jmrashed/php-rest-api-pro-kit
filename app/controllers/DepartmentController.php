<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Response;
use App\Models\Department;

class DepartmentController extends Controller
{
    public function index()
    {
        $perPage = $this->request->get('per_page', 10);
        $page = $this->request->get('page', 1);
        $departments = Department::paginate($perPage, $page);
        return Response::success('Departments retrieved successfully', $departments);
    }

    public function show($id)
    {
        $department = Department::find($id);
        if (!$department) {
            return Response::error('Department not found', 404);
        }
        return Response::success('Department retrieved successfully', $department);
    }

    public function store()
    {
        $data = $this->request->json();
        $department = new Department();
        $department->name = $data['name'] ?? null;
        $department->description = $data['description'] ?? null;

        if ($department->save()) {
            return Response::success('Department created successfully', ['department' => $department], 201);
        }
        return Response::error('Failed to create department', 500);
    }

    public function update($id)
    {
        $department = Department::find($id);
        if (!$department) {
            return Response::error('Department not found', 404);
        }

        $data = $this->request->json();
        $department->name = $data['name'] ?? $department->name;
        $department->description = $data['description'] ?? $department->description;

        if ($department->save()) {
            return Response::success('Department updated successfully', ['department' => $department]);
        }
        return Response::error('Failed to update department', 500);
    }

    public function destroy($id)
    {
        $department = Department::find($id);
        if (!$department) {
            return Response::error('Department not found', 404);
        }

        if ($department->delete()) {
            return Response::success('Department deleted successfully');
        }
        return Response::error('Failed to delete department', 500);
    }
}
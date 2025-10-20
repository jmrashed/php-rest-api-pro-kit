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
        return Response::json($departments);
    }

    public function show($id)
    {
        $department = Department::find($id);
        if (!$department) {
            return Response::json(['error' => 'Department not found'], 404);
        }
        return Response::json($department);
    }

    public function store()
    {
        $data = $this->request->json();
        $department = new Department();
        $department->name = $data['name'] ?? null;
        $department->description = $data['description'] ?? null;

        if ($department->save()) {
            return Response::json(['message' => 'Department created successfully', 'department' => $department], 201);
        }
        return Response::json(['error' => 'Failed to create department'], 500);
    }

    public function update($id)
    {
        $department = Department::find($id);
        if (!$department) {
            return Response::json(['error' => 'Department not found'], 404);
        }

        $data = $this->request->json();
        $department->name = $data['name'] ?? $department->name;
        $department->description = $data['description'] ?? $department->description;

        if ($department->save()) {
            return Response::json(['message' => 'Department updated successfully', 'department' => $department]);
        }
        return Response::json(['error' => 'Failed to update department'], 500);
    }

    public function destroy($id)
    {
        $department = Department::find($id);
        if (!$department) {
            return Response::json(['error' => 'Department not found'], 404);
        }

        if ($department->delete()) {
            return Response::json(['message' => 'Department deleted successfully']);
        }
        return Response::json(['error' => 'Failed to delete department'], 500);
    }
}
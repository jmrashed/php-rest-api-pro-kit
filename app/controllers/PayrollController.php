<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Response;
use App\Models\Payroll;

class PayrollController extends Controller
{
    public function index()
    {
        $perPage = $this->request->get('per_page', 10);
        $page = $this->request->get('page', 1);
        $payrollRecords = Payroll::paginate($perPage, $page);
        return Response::json($payrollRecords);
    }

    public function show($id)
    {
        $payroll = Payroll::find($id);
        if (!$payroll) {
            return Response::json(['error' => 'Payroll record not found'], 404);
        }
        return Response::json($payroll);
    }

    public function store()
    {
        $data = $this->request->json();
        $payroll = new Payroll();
        $payroll->employee_id = $data['employee_id'] ?? null;
        $payroll->pay_date = $data['pay_date'] ?? null;
        $payroll->base_salary = $data['base_salary'] ?? null;
        $payroll->bonuses = $data['bonuses'] ?? 0;
        $payroll->deductions = $data['deductions'] ?? 0;
        $payroll->net_salary = $data['net_salary'] ?? null;

        if ($payroll->save()) {
            return Response::json(['message' => 'Payroll record created successfully', 'payroll' => $payroll], 201);
        }
        return Response::json(['error' => 'Failed to create payroll record'], 500);
    }

    public function update($id)
    {
        $payroll = Payroll::find($id);
        if (!$payroll) {
            return Response::json(['error' => 'Payroll record not found'], 404);
        }

        $data = $this->request->json();
        $payroll->employee_id = $data['employee_id'] ?? $payroll->employee_id;
        $payroll->pay_date = $data['pay_date'] ?? $payroll->pay_date;
        $payroll->base_salary = $data['base_salary'] ?? $payroll->base_salary;
        $payroll->bonuses = $data['bonuses'] ?? $payroll->bonuses;
        $payroll->deductions = $data['deductions'] ?? $payroll->deductions;
        $payroll->net_salary = $data['net_salary'] ?? $payroll->net_salary;

        if ($payroll->save()) {
            return Response::json(['message' => 'Payroll record updated successfully', 'payroll' => $payroll]);
        }
        return Response::json(['error' => 'Failed to update payroll record'], 500);
    }

    public function destroy($id)
    {
        $payroll = Payroll::find($id);
        if (!$payroll) {
            return Response::json(['error' => 'Payroll record not found'], 404);
        }

        if ($payroll->delete()) {
            return Response::json(['message' => 'Payroll record deleted successfully']);
        }
        return Response::json(['error' => 'Failed to delete payroll record'], 500);
    }
}
<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Response;
use App\Models\PerformanceReview;

class PerformanceReviewController extends Controller
{
    public function index()
    {
        $perPage = $this->request->get('per_page', 10);
        $page = $this->request->get('page', 1);
        $performanceReviews = PerformanceReview::paginate($perPage, $page);
        return Response::json($performanceReviews);
    }

    public function show($id)
    {
        $performanceReview = PerformanceReview::find($id);
        if (!$performanceReview) {
            return Response::json(['error' => 'Performance Review not found'], 404);
        }
        return Response::json($performanceReview);
    }

    public function store()
    {
        $data = $this->request->json();
        $performanceReview = new PerformanceReview();
        $performanceReview->employee_id = $data['employee_id'] ?? null;
        $performanceReview->reviewer_id = $data['reviewer_id'] ?? null;
        $performanceReview->review_date = $data['review_date'] ?? null;
        $performanceReview->rating = $data['rating'] ?? null;
        $performanceReview->comments = $data['comments'] ?? null;

        if ($performanceReview->save()) {
            return Response::json(['message' => 'Performance Review created successfully', 'performance_review' => $performanceReview], 201);
        }
        return Response::json(['error' => 'Failed to create performance review'], 500);
    }

    public function update($id)
    {
        $performanceReview = PerformanceReview::find($id);
        if (!$performanceReview) {
            return Response::json(['error' => 'Performance Review not found'], 404);
        }

        $data = $this->request->json();
        $performanceReview->employee_id = $data['employee_id'] ?? $performanceReview->employee_id;
        $performanceReview->reviewer_id = $data['reviewer_id'] ?? $performanceReview->reviewer_id;
        $performanceReview->review_date = $data['review_date'] ?? $performanceReview->review_date;
        $performanceReview->rating = $data['rating'] ?? $performanceReview->rating;
        $performanceReview->comments = $data['comments'] ?? $performanceReview->comments;

        if ($performanceReview->save()) {
            return Response::json(['message' => 'Performance Review updated successfully', 'performance_review' => $performanceReview]);
        }
        return Response::json(['error' => 'Failed to update performance review'], 500);
    }

    public function destroy($id)
    {
        $performanceReview = PerformanceReview::find($id);
        if (!$performanceReview) {
            return Response::json(['error' => 'Performance Review not found'], 404);
        }

        if ($performanceReview->delete()) {
            return Response::json(['message' => 'Performance Review deleted successfully']);
        }
        return Response::json(['error' => 'Failed to delete performance review'], 500);
    }
}
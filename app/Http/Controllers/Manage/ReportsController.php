<?php

namespace App\Http\Controllers\Manage;

use App\Entities\Reports\Report; // Correct Model Namespace
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReportsController extends Controller
{
    public function index(Request $request)
    {
        $query = Report::with(['user', 'reportedUser']);

        if ($request->has('q')) {
            $search = $request->q;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $allItems = $query->latest()->paginate(20);

        return view('manage.reports.index', [
            'pageTitle' => 'Manage Reports',
            'allItems' => $allItems,
        ]);
    }

    public function show($id)
    {
        $entity = Report::with(['user', 'reportedUser'])->findOrFail($id);
        return view('manage.reports.show', [
            'pageTitle' => 'Report Details',
            'entity' => $entity,
        ]);
    }

    public function destroy($id)
    {
        $entity = Report::findOrFail($id);
        $entity->delete();

        return redirect()->route('manage.reports.index')->with('success', 'Report deleted successfully.');
    }
}

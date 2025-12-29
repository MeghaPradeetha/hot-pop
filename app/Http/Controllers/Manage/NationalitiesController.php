<?php

namespace App\Http\Controllers\Manage;

use App\Entities\Nationalities\Nationality;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NationalitiesController extends Controller
{
    public function index()
    {
        $allItems = Nationality::latest()->paginate(20);

        return view('manage.nationalities.index', [
            'pageTitle' => 'Nationalities',
            'allItems' => $allItems,
        ]);
    }

    public function create()
    {
        return view('manage.nationalities.form', [
            'pageTitle' => 'Add Nationality',
            'entity' => new Nationality(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        Nationality::create($validated);

        return redirect()->route('manage.nationality.index')->with('success', 'Nationality created successfully.');
    }

    public function edit($id)
    {
        $entity = Nationality::findOrFail($id);
        
        return view('manage.nationalities.form', [
            'pageTitle' => 'Edit Nationality',
            'entity' => $entity,
        ]);
    }

    public function update(Request $request, $id)
    {
        $entity = Nationality::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        $entity->update($validated);

        return redirect()->route('manage.nationality.index')->with('success', 'Nationality updated successfully.');
    }

    public function destroy($id)
    {
        $entity = Nationality::findOrFail($id);
        $entity->delete();

        return redirect()->route('manage.nationality.index')->with('success', 'Nationality deleted successfully.');
    }

    public function show($id)
    {
        $entity = Nationality::findOrFail($id);

        return view('manage.nationalities.show', [
            'pageTitle' => 'Nationality Details',
            'entity' => $entity,
        ]);
    }
}

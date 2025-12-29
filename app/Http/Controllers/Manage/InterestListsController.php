<?php

namespace App\Http\Controllers\Manage;

use App\Entities\InterestLists\InterestList;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class InterestListsController extends Controller
{
    public function index()
    {
        $allItems = InterestList::latest()->paginate(20);
        return view('manage.interest-lists.index', [
            'pageTitle' => 'Interest Lists',
            'allItems' => $allItems,
        ]);
    }

    public function create()
    {
        return view('manage.interest-lists.form', [
            'pageTitle' => 'Add Interest List',
            'entity' => new InterestList(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        InterestList::create($validated);

        return redirect()->route('manage.interests.index')->with('success', 'Interest List created successfully.');
    }

    public function edit($id)
    {
        $entity = InterestList::findOrFail($id);
        return view('manage.interest-lists.form', [
            'pageTitle' => 'Edit Interest List',
            'entity' => $entity,
        ]);
    }

    public function update(Request $request, $id)
    {
        $entity = InterestList::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        $entity->update($validated);

        return redirect()->route('manage.interests.index')->with('success', 'Interest List updated successfully.');
    }

    public function destroy($id)
    {
        $entity = InterestList::findOrFail($id);
        $entity->delete();

        return redirect()->route('manage.interests.index')->with('success', 'Interest List deleted successfully.');
    }

    public function show($id)
    {
        $entity = InterestList::findOrFail($id);
        return view('manage.interest-lists.show', [
            'pageTitle' => 'Interest List Details',
            'entity' => $entity,
        ]);
    }
}

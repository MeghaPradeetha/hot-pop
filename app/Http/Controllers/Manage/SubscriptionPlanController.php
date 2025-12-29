<?php

namespace App\Http\Controllers\Manage;

use App\Entities\SubscriptionPlans\SubscriptionPlan;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SubscriptionPlanController extends Controller
{
    public function index()
    {
        $allItems = SubscriptionPlan::latest()->paginate(20);

        return view('manage.subscription-plan.index', [
            'pageTitle' => 'Subscription Plans',
            'allItems' => $allItems,
        ]);
    }

    public function create()
    {
        return view('manage.subscription-plan.form', [
            'pageTitle' => 'Add Subscription Plan',
            'entity' => new SubscriptionPlan(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'plan_id' => 'required|string|max:255',
            'price' => 'required|numeric',
            'description' => 'nullable|string',
        ]);

        SubscriptionPlan::create($validated);

        return redirect()->route('manage.subs-plans.index')->with('success', 'Plan created successfully.');
    }

    public function edit($id)
    {
        $entity = SubscriptionPlan::findOrFail($id);
        
        return view('manage.subscription-plan.form', [
            'pageTitle' => 'Edit Subscription Plan',
            'entity' => $entity,
        ]);
    }

    public function update(Request $request, $id)
    {
        $entity = SubscriptionPlan::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'plan_id' => 'required|string|max:255',
            'price' => 'required|numeric',
            'description' => 'nullable|string',
        ]);

        $entity->update($validated);

        return redirect()->route('manage.subs-plans.index')->with('success', 'Plan updated successfully.');
    }

    public function destroy($id)
    {
        $entity = SubscriptionPlan::findOrFail($id);
        $entity->delete();

        return redirect()->route('manage.subs-plans.index')->with('success', 'Plan deleted successfully.');
    }

    public function show($id)
    {
        $entity = SubscriptionPlan::findOrFail($id);
        return view('manage.subscription-plan.show', [
            'pageTitle' => 'Plan Details',
            'entity' => $entity,
        ]);
    }
}

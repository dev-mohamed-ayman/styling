<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\StylistFeature;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StylistFeaturesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $features = StylistFeature::with('stylist')->latest()->paginate(10);
        return view('dashboard.pages.stylist_features', compact('features'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.pages.stylist_features');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'stylist_id' => 'required|exists:stylists,id',
            'icon'       => 'required|string|max:255',
            'title'      => 'required|string|max:255',
        ]);

        StylistFeature::create($validatedData);

        return redirect()->route('dashboard.stylist_features')
            ->with('success', 'Feature created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(StylistFeature $stylistFeature)
    {
        return view('dashboard.pages.stylist_features', compact('stylistFeature'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(StylistFeature $stylistFeature)
    {
        return view('dashboard.pages.stylist_features', compact('stylistFeature'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, StylistFeature $stylistFeature)
    {
        $validatedData = $request->validate([
            'stylist_id' => 'required|exists:stylists,id',
            'icon'       => 'required|string|max:255',
            'title'      => 'required|string|max:255',
        ]);

        $stylistFeature->update($validatedData);

        return redirect()->route('dashboard.stylist_features')
            ->with('success', 'Feature updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StylistFeature $stylistFeature)
    {
        $stylistFeature->delete();

        return redirect()->route('dashboard.stylist_features')
            ->with('success', 'Feature deleted successfully.');
    }
}

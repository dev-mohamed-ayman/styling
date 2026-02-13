<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\StylistService;
use Illuminate\Http\Request;

class StylistServicesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $services = StylistService::with('stylist')->latest()->paginate(10);

        return view('dashboard.pages.stylist_services', compact('services'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.pages.stylist_services');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'stylist_id' => 'required|exists:stylists,id',
            'title' => 'required|string|max:255',
            'available' => 'boolean',
            'price' => 'required|numeric|min:0',
        ]);

        $validatedData['available'] = $request->boolean('available', true);

        StylistService::create($validatedData);

        return redirect()->route('dashboard.stylist_services.index')
            ->with('success', 'Service created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(StylistService $stylistService)
    {
        return view('dashboard.pages.stylist_services', compact('stylistService'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(StylistService $stylistService)
    {
        return view('dashboard.pages.stylist_services', compact('stylistService'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, StylistService $stylistService)
    {
        $validatedData = $request->validate([
            'stylist_id' => 'required|exists:stylists,id',
            'title' => 'required|string|max:255',
            'available' => 'boolean',
            'price' => 'required|numeric|min:0',
        ]);

        $validatedData['available'] = $request->boolean('available', true);

        $stylistService->update($validatedData);

        return redirect()->route('dashboard.stylist_services.index')
            ->with('success', 'Service updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StylistService $stylistService)
    {
        $stylistService->delete();

        return redirect()->route('dashboard.stylist_services.index')
            ->with('success', 'Service deleted successfully.');
    }
}

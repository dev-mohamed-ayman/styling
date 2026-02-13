<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\StylistImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StylistImagesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $images = StylistImage::with('stylist')->latest()->paginate(10);

        return view('dashboard.pages.stylist_images', compact('images'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.pages.stylist_images');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'stylist_id' => 'required|exists:stylists,id',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $validatedData['path'] = $request->file('image')->store('stylist_images', 'public');
        }

        StylistImage::create($validatedData);

        return redirect()->route('dashboard.stylist_images.index')
            ->with('success', 'Image created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(StylistImage $stylistImage)
    {
        return view('dashboard.pages.stylist_images', compact('stylistImage'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(StylistImage $stylistImage)
    {
        return view('dashboard.pages.stylist_images', compact('stylistImage'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, StylistImage $stylistImage)
    {
        $validatedData = $request->validate([
            'stylist_id' => 'required|exists:stylists,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($stylistImage->path) {
                Storage::disk('public')->delete($stylistImage->getRawOriginal('path'));
            }
            $validatedData['path'] = $request->file('image')->store('stylist_images', 'public');
        }

        $stylistImage->update($validatedData);

        return redirect()->route('dashboard.stylist_images.index')
            ->with('success', 'Image updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StylistImage $stylistImage)
    {
        // Delete image file if exists
        if ($stylistImage->path) {
            Storage::disk('public')->delete($stylistImage->getRawOriginal('path'));
        }

        $stylistImage->delete();

        return redirect()->route('dashboard.stylist_images.index')
            ->with('success', 'Image deleted successfully.');
    }
}

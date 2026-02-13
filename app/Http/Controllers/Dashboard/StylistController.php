<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Stylist;
use Illuminate\Http\Request;

class StylistController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return view('dashboard.pages.stylists');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.stylists.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'cover' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'bio' => 'nullable|string',
            'about' => 'nullable|string',
            'price' => 'required|numeric|min:0',
        ]);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('stylists', 'public');
            $validatedData['image'] = $imagePath;
        }

        if ($request->hasFile('cover')) {
            $coverPath = $request->file('cover')->store('stylists/covers', 'public');
            $validatedData['cover'] = $coverPath;
        }

        $validatedData['avg_rating'] = 0;
        $validatedData['reviews_count'] = 0;

        Stylist::create($validatedData);

        return redirect()->route('dashboard.stylists.index')
            ->with('success', 'Stylist created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Stylist $stylist)
    {
        return view('dashboard.stylists.show', compact('stylist'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Stylist $stylist)
    {
        return view('dashboard.stylists.edit', compact('stylist'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Stylist $stylist)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'cover' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'bio' => 'nullable|string',
            'about' => 'nullable|string',
            'price' => 'required|numeric|min:0',
        ]);

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($stylist->image) {
                \Storage::disk('public')->delete($stylist->image);
            }
            $imagePath = $request->file('image')->store('stylists', 'public');
            $validatedData['image'] = $imagePath;
        }

        if ($request->hasFile('cover')) {
            // Delete old cover if exists
            if ($stylist->cover) {
                \Storage::disk('public')->delete($stylist->cover);
            }
            $coverPath = $request->file('cover')->store('stylists/covers', 'public');
            $validatedData['cover'] = $coverPath;
        }

        $stylist->update($validatedData);

        return redirect()->route('dashboard.stylists.index')
            ->with('success', 'Stylist updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Stylist $stylist)
    {
        // Delete image if exists
        if ($stylist->image) {
            \Storage::disk('public')->delete($stylist->image);
        }

        // Delete cover if exists
        if ($stylist->cover) {
            \Storage::disk('public')->delete($stylist->cover);
        }

        $stylist->fashionStyles()->detach();
        $stylist->delete();

        return redirect()->route('dashboard.stylists.index')
            ->with('success', 'Stylist deleted successfully.');
    }
}

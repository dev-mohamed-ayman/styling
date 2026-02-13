<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\FashionStyle;
use Illuminate\Http\Request;

class FashionStyleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return view('dashboard.pages.fashion_styles');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.fashion_styles.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('public/fashion_styles');
            $validatedData['image'] = str_replace('public/', '', $imagePath);
        }

        FashionStyle::create($validatedData);

        return redirect()->route('dashboard.fashion_styles.index')
            ->with('success', 'Fashion Style created successfully.');

    }

    /**
     * Display the specified resource.
     */
    public function show(FashionStyle $fashionStyle)
    {
        return view('dashboard.fashion_styles.show', compact('fashionStyle'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FashionStyle $fashionStyle)
    {
        return view('dashboard.fashion_styles.edit', compact('fashionStyle'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, FashionStyle $fashionStyle)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($fashionStyle->image) {
                Storage::delete('public/' . $fashionStyle->image);
            }

            $imagePath = $request->file('image')->store('public/fashion_styles');
            $validatedData['image'] = str_replace('public/', '', $imagePath);
        }

        $fashionStyle->update($validatedData);

        return redirect()->route('dashboard.fashion_styles.index')
            ->with('success', 'Fashion Style updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FashionStyle $fashionStyle)
    {
        // Delete image if exists
        if ($fashionStyle->image) {
            Storage::delete('public/' . $fashionStyle->image);
        }

        $fashionStyle->delete();

        return redirect()->route('dashboard.fashion_styles.index')
            ->with('success', 'Fashion Style deleted successfully.');
    }
}

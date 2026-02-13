<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\StylistReview;
use Illuminate\Http\Request;

class StylistReviewsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $reviews = StylistReview::with(['stylist', 'user'])->latest()->paginate(10);

        return view('dashboard.pages.stylist_reviews', compact('reviews'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.pages.stylist_reviews');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'stylist_id' => 'required|exists:stylists,id',
            'user_id' => 'required|exists:users,id',
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'required|string',
        ]);

        StylistReview::create($validatedData);

        return redirect()->route('dashboard.stylist_reviews.index')
            ->with('success', 'Review created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(StylistReview $stylistReview)
    {
        return view('dashboard.pages.stylist_reviews', compact('stylistReview'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(StylistReview $stylistReview)
    {
        return view('dashboard.pages.stylist_reviews', compact('stylistReview'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, StylistReview $stylistReview)
    {
        $validatedData = $request->validate([
            'stylist_id' => 'required|exists:stylists,id',
            'user_id' => 'required|exists:users,id',
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'required|string',
        ]);

        $stylistReview->update($validatedData);

        return redirect()->route('dashboard.stylist_reviews.index')
            ->with('success', 'Review updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StylistReview $stylistReview)
    {
        $stylistReview->delete();

        return redirect()->route('dashboard.stylist_reviews.index')
            ->with('success', 'Review deleted successfully.');
    }
}

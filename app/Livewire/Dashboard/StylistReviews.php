<?php

namespace App\Livewire\Dashboard;

use App\Models\Stylist;
use App\Models\StylistReview;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class StylistReviews extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $per_page = 10;

    public $search = '';

    // Modal properties
    public $is_open = false;

    public $review_id = null;

    public $stylist_id = null;

    public $user_id = null;

    public $rating = 5;

    public $review = '';

    protected function rules()
    {
        return [
            'stylist_id' => 'required|exists:stylists,id',
            'user_id' => 'required|exists:users,id',
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'required|string',
        ];
    }

    protected $messages = [
        'stylist_id.required' => 'The stylist field is required.',
        'user_id.required' => 'The user field is required.',
        'rating.required' => 'The rating field is required.',
        'rating.min' => 'The rating must be at least 1.',
        'rating.max' => 'The rating may not be greater than 5.',
        'review.required' => 'The review field is required.',
    ];

    public function openModal()
    {
        $this->resetInput();
        $this->is_open = true;
    }

    public function closeModal()
    {
        $this->is_open = false;
        $this->resetInput();
    }

    private function resetInput()
    {
        $this->review_id = null;
        $this->stylist_id = null;
        $this->user_id = null;
        $this->rating = 5;
        $this->review = '';
    }

    public function create()
    {
        $this->openModal();
    }

    public function edit($id)
    {
        $stylistReview = StylistReview::findOrFail($id);
        $this->review_id = $id;
        $this->stylist_id = $stylistReview->stylist_id;
        $this->user_id = $stylistReview->user_id;
        $this->rating = $stylistReview->rating;
        $this->review = $stylistReview->review;
        $this->is_open = true;
    }

    public function store()
    {
        $this->validate();

        StylistReview::create([
            'stylist_id' => $this->stylist_id,
            'user_id' => $this->user_id,
            'rating' => $this->rating,
            'review' => $this->review,
        ]);

        $this->closeModal();
        $this->dispatch('toast', ['type' => 'success', 'message' => __('Review created successfully')]);
    }

    public function update()
    {
        $this->validate();

        $stylistReview = StylistReview::findOrFail($this->review_id);

        $stylistReview->update([
            'stylist_id' => $this->stylist_id,
            'user_id' => $this->user_id,
            'rating' => $this->rating,
            'review' => $this->review,
        ]);

        $this->closeModal();
        $this->dispatch('toast', ['type' => 'success', 'message' => __('Review updated successfully')]);
    }

    public function delete($id)
    {
        StylistReview::findOrFail($id)->delete();

        $this->dispatch('toast', ['type' => 'success', 'message' => __('Review deleted successfully')]);
    }

    public function render()
    {
        $reviews = StylistReview::with(['stylist', 'user'])
            ->where('review', 'like', '%'.$this->search.'%')
            ->orWhereHas('stylist', function ($query) {
                $query->where('name', 'like', '%'.$this->search.'%');
            })
            ->orWhereHas('user', function ($query) {
                $query->where('name', 'like', '%'.$this->search.'%');
            })
            ->latest()
            ->paginate($this->per_page);

        $stylists = Stylist::all();
        $users = User::all();

        return view('livewire.dashboard.stylist_reviews', [
            'stylist_reviews' => $reviews,
            'stylists' => $stylists,
            'users' => $users,
        ]);
    }
}

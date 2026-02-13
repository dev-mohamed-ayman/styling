<?php

namespace App\Livewire\Dashboard;

use App\Models\Stylist;
use App\Models\StylistImage;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class StylistImages extends Component
{
    use WithPagination;
    use WithFileUploads;

    protected $paginationTheme = 'bootstrap';

    public $per_page = 10;

    public $search = '';

    // Modal properties
    public $is_open = false;

    public $image_id = null;

    public $stylist_id = null;

    public $image = null;

    public $old_image = null;

    protected function rules()
    {
        $rules = [
            'stylist_id' => 'required|exists:stylists,id',
        ];

        if ($this->image_id) {
            // Update case - image is optional
            $rules['image'] = 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048';
        } else {
            // Create case - image is required
            $rules['image'] = 'required|image|mimes:jpeg,png,jpg,gif|max:2048';
        }

        return $rules;
    }

    protected $messages = [
        'stylist_id.required' => 'The stylist field is required.',
        'image.required' => 'The image field is required.',
        'image.image' => 'The file must be an image.',
        'image.mimes' => 'The image must be a file of type: jpeg, png, jpg, gif.',
        'image.max' => 'The image may not be greater than 2MB.',
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
        $this->image_id = null;
        $this->stylist_id = null;
        $this->image = null;
        $this->old_image = null;
    }

    public function create()
    {
        $this->openModal();
    }

    public function edit($id)
    {
        $stylistImage = StylistImage::findOrFail($id);
        $this->image_id = $id;
        $this->stylist_id = $stylistImage->stylist_id;
        $this->old_image = $stylistImage->getRawOriginal('path');
        $this->is_open = true;
    }

    public function store()
    {
        $this->validate();

        $imagePath = null;

        if ($this->image) {
            $imagePath = $this->image->store('stylist_images', 'public');
        }

        StylistImage::create([
            'stylist_id' => $this->stylist_id,
            'path' => $imagePath,
        ]);

        $this->closeModal();
        $this->dispatch('toast', ['type' => 'success', 'message' => __('Image created successfully')]);
    }

    public function update()
    {
        $this->validate();

        $stylistImage = StylistImage::findOrFail($this->image_id);

        $imagePath = $this->old_image;

        if ($this->image) {
            // Delete old image if exists
            if ($stylistImage->path && Storage::disk('public')->exists($stylistImage->getRawOriginal('path'))) {
                Storage::disk('public')->delete($stylistImage->getRawOriginal('path'));
            }
            // Store new image
            $imagePath = $this->image->store('stylist_images', 'public');
        }

        $stylistImage->update([
            'stylist_id' => $this->stylist_id,
            'path' => $imagePath,
        ]);

        $this->closeModal();
        $this->dispatch('toast', ['type' => 'success', 'message' => __('Image updated successfully')]);
    }

    public function delete($id)
    {
        $stylistImage = StylistImage::findOrFail($id);

        // Delete image file if exists
        if ($stylistImage->path && Storage::disk('public')->exists($stylistImage->getRawOriginal('path'))) {
            Storage::disk('public')->delete($stylistImage->getRawOriginal('path'));
        }

        $stylistImage->delete();

        $this->dispatch('toast', ['type' => 'success', 'message' => __('Image deleted successfully')]);
    }

    public function render()
    {
        $images = StylistImage::with('stylist')
            ->whereHas('stylist', function ($query) {
                $query->where('name', 'like', '%'.$this->search.'%');
            })
            ->orWhere('id', 'like', '%'.$this->search.'%')
            ->latest()
            ->paginate($this->per_page);

        $stylists = Stylist::all();

        return view('livewire.dashboard.stylist_images', [
            'stylist_images' => $images,
            'stylists' => $stylists,
        ]);
    }
}

<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\Stylist;
use App\Models\FashionStyle;
use Illuminate\Support\Facades\Storage;

class Stylists extends Component
{
    use WithPagination, WithFileUploads;

    protected $paginationTheme = 'bootstrap';
    public $per_page = 10;
    public $search = '';
    protected $stylists;

    // Modal properties
    public $is_open = false;
    public $stylist_id = null;
    public $name = '';
    public $image;
    public $cover;
    public $bio = '';
    public $about = '';
    public $price = 0;
    public $old_image = null;
    public $old_cover = null;
    public $selected_fashion_styles = [];

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'cover' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'bio' => 'nullable|string',
            'about' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'selected_fashion_styles' => 'nullable|array',
        ];
    }

    protected $messages = [
        'name.required' => 'The name field is required.',
        'price.required' => 'The price field is required.',
        'image.image' => 'The file must be an image.',
        'image.mimes' => 'The image must be a file of type: jpeg, png, jpg, gif, svg.',
        'image.max' => 'The image size must not exceed 2MB.',
        'cover.image' => 'The file must be an image.',
        'cover.mimes' => 'The cover must be a file of type: jpeg, png, jpg, gif, svg.',
        'cover.max' => 'The cover size must not exceed 2MB.',
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
        $this->name = '';
        $this->image = null;
        $this->cover = null;
        $this->bio = '';
        $this->about = '';
        $this->price = 0;
        $this->old_image = null;
        $this->old_cover = null;
        $this->stylist_id = null;
        $this->selected_fashion_styles = [];
    }

    public function create()
    {
        $this->openModal();
    }

    public function edit($id)
    {
        $stylist = Stylist::findOrFail($id);
        $this->stylist_id = $id;
        $this->name = $stylist->name;
        $this->old_image = $stylist->image;
        $this->old_cover = $stylist->cover;
        $this->bio = $stylist->bio;
        $this->about = $stylist->about;
        $this->price = $stylist->price;
        $this->selected_fashion_styles = $stylist->fashionStyles->pluck('id')->toArray();
        $this->is_open = true;
    }

    public function store()
    {
        $this->validate();

        $imagePath = $this->old_image;
        $coverPath = $this->old_cover;

        if ($this->image) {
            // Delete old image if exists
            if ($this->old_image && Storage::disk('public')->exists($this->old_image)) {
                Storage::disk('public')->delete($this->old_image);
            }
            // Store new image
            $imagePath = $this->image->store('stylists', 'public');
        }

        if ($this->cover) {
            // Delete old cover if exists
            if ($this->old_cover && Storage::disk('public')->exists($this->old_cover)) {
                Storage::disk('public')->delete($this->old_cover);
            }
            // Store new cover
            $coverPath = $this->cover->store('stylists/covers', 'public');
        }

        $stylist = Stylist::create([
            'name' => $this->name,
            'image' => $imagePath,
            'cover' => $coverPath,
            'bio' => $this->bio,
            'about' => $this->about,
            'price' => $this->price,
            'avg_rating' => 0,
            'reviews_count' => 0,
        ]);

        if (!empty($this->selected_fashion_styles)) {
            $stylist->fashionStyles()->sync($this->selected_fashion_styles);
        }

        $this->closeModal();
        $this->dispatch('toast', ['type' => 'success', 'message' => __('Stylist created successfully')]);
    }

    public function update()
    {
        $this->validate();

        $stylist = Stylist::findOrFail($this->stylist_id);

        $imagePath = $this->old_image;
        $coverPath = $this->old_cover;

        if ($this->image) {
            // Delete old image if exists
            if ($stylist->image && Storage::disk('public')->exists($stylist->image)) {
                Storage::disk('public')->delete($stylist->image);
            }
            // Store new image
            $imagePath = $this->image->store('stylists', 'public');
        }

        if ($this->cover) {
            // Delete old cover if exists
            if ($stylist->cover && Storage::disk('public')->exists($stylist->cover)) {
                Storage::disk('public')->delete($stylist->cover);
            }
            // Store new cover
            $coverPath = $this->cover->store('stylists/covers', 'public');
        }

        $stylist->update([
            'name' => $this->name,
            'image' => $imagePath,
            'cover' => $coverPath,
            'bio' => $this->bio,
            'about' => $this->about,
            'price' => $this->price,
        ]);

        $stylist->fashionStyles()->sync($this->selected_fashion_styles ?? []);

        $this->closeModal();
        $this->dispatch('toast', ['type' => 'success', 'message' => __('Stylist updated successfully')]);
    }

    public function delete($id)
    {
        $stylist = Stylist::findOrFail($id);

        // Delete image if exists
        if ($stylist->image && Storage::disk('public')->exists($stylist->image)) {
            Storage::disk('public')->delete($stylist->image);
        }

        // Delete cover if exists
        if ($stylist->cover && Storage::disk('public')->exists($stylist->cover)) {
            Storage::disk('public')->delete($stylist->cover);
        }

        $stylist->fashionStyles()->detach();
        $stylist->delete();

        $this->dispatch('toast', ['type' => 'success', 'message' => __('Stylist deleted successfully')]);
    }

    public function render()
    {
        $this->stylists = Stylist::query()
            ->where('name', 'like', '%' . $this->search . '%')
            ->orWhere('bio', 'like', '%' . $this->search . '%')
            ->latest()->paginate($this->per_page);

        $fashion_styles = FashionStyle::all();

        return view('livewire.dashboard.stylists', [
            'stylists' => $this->stylists,
            'fashion_styles' => $fashion_styles
        ]);
    }
}

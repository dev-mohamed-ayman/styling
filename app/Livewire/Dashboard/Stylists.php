<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\Stylist;
use App\Models\StylistFeature;
use App\Models\StylistImage;
use App\Models\StylistService;
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

    // Stylist fields
    public $name = '';
    public $image;
    public $old_image = null;
    public $cover;
    public $old_cover = null;
    public $bio = '';
    public $about = '';
    public $price = 0;

    // Related data
    public $features = [];
    public $images = [];
    public $services = [];
    public $selected_fashion_styles = [];

    // Temp images for upload
    public $new_images = [];
    public $removed_images = [];

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'cover' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'bio' => 'nullable|string',
            'about' => 'nullable|string',
            'price' => 'nullable|numeric|min:0',
            'features' => 'nullable|array',
            'features.*.icon' => 'nullable|string|max:255',
            'features.*.title' => 'nullable|string|max:255',
            'services' => 'nullable|array',
            'services.*.title' => 'nullable|string|max:255',
            'services.*.price' => 'nullable|numeric|min:0',
            'services.*.available' => 'nullable|boolean',
            'selected_fashion_styles' => 'nullable|array',
            'selected_fashion_styles.*' => 'exists:fashion_styles,id',
        ];
    }

    protected $messages = [
        'name.required' => 'The name field is required.',
        'image.image' => 'The file must be an image.',
        'image.mimes' => 'The image must be a file of type: jpeg, png, jpg, gif, svg.',
        'image.max' => 'The image size must not exceed 2MB.',
        'cover.image' => 'The cover file must be an image.',
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
        $this->stylist_id = null;
        $this->name = '';
        $this->image = null;
        $this->old_image = null;
        $this->cover = null;
        $this->old_cover = null;
        $this->bio = '';
        $this->about = '';
        $this->price = 0;
        $this->features = [];
        $this->images = [];
        $this->services = [];
        $this->selected_fashion_styles = [];
        $this->new_images = [];
        $this->removed_images = [];
    }

    public function create()
    {
        // Initialize empty arrays for new record
        $this->features = [
            ['icon' => '', 'title' => '']
        ];
        $this->services = [
            ['title' => '', 'price' => 0, 'available' => true]
        ];
        $this->openModal();
    }

    public function edit($id)
    {
        $stylist = Stylist::with(['features', 'images', 'services'])->findOrFail($id);

        $this->stylist_id = $id;
        $this->name = $stylist->name;
        $this->old_image = $stylist->image;
        $this->old_cover = $stylist->cover;
        $this->bio = $stylist->bio;
        $this->about = $stylist->about;
        $this->price = $stylist->price;

        // Load features
        $this->features = $stylist->features->map(function ($feature) {
            return [
                'id' => $feature->id,
                'icon' => $feature->icon,
                'title' => $feature->title,
            ];
        })->toArray();

        if (empty($this->features)) {
            $this->features = [['icon' => '', 'title' => '']];
        }

        // Load services
        $this->services = $stylist->services->map(function ($service) {
            return [
                'id' => $service->id,
                'title' => $service->title,
                'price' => $service->price,
                'available' => $service->available,
            ];
        })->toArray();

        if (empty($this->services)) {
            $this->services = [['title' => '', 'price' => 0, 'available' => true]];
        }

        // Load existing images
        $this->images = $stylist->images->map(function ($image) {
            return [
                'id' => $image->id,
                'path' => $image->path,
            ];
        })->toArray();

        // Load fashion styles
        $this->selected_fashion_styles = $stylist->fashionStyles->pluck('id')->toArray();

        $this->is_open = true;
    }

    public function store()
    {
        $this->validate();

        $imagePath = null;
        if ($this->image) {
            $imagePath = $this->image->store('stylists', 'public');
        }

        $coverPath = null;
        if ($this->cover) {
            $coverPath = $this->cover->store('stylists/covers', 'public');
        }

        $stylist = Stylist::create([
            'name' => $this->name,
            'image' => $imagePath,
            'cover' => $coverPath,
            'bio' => $this->bio,
            'about' => $this->about,
            'price' => $this->price,
        ]);

        // Save features
        $this->saveFeatures($stylist);

        // Save services
        $this->saveServices($stylist);

        // Save fashion styles
        $stylist->fashionStyles()->sync($this->selected_fashion_styles);

        // Save new images
        $this->saveImages($stylist);

        $this->closeModal();
        $this->dispatch('toast', ['type' => 'success', 'message' => __('Create Stylist: :name', ['name' => $stylist->name])]);
    }

    public function update()
    {
        $this->validate();

        $stylist = Stylist::findOrFail($this->stylist_id);

        $imagePath = $this->old_image;
        if ($this->image) {
            if ($stylist->image && Storage::disk('public')->exists($stylist->image)) {
                Storage::disk('public')->delete($stylist->image);
            }
            $imagePath = $this->image->store('stylists', 'public');
        }

        $coverPath = $this->old_cover;
        if ($this->cover) {
            if ($stylist->cover && Storage::disk('public')->exists($stylist->cover)) {
                Storage::disk('public')->delete($stylist->cover);
            }
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

        // Update features
        $this->saveFeatures($stylist);

        // Update services
        $this->saveServices($stylist);

        // Update fashion styles
        $stylist->fashionStyles()->sync($this->selected_fashion_styles);

        // Handle images
        $this->handleImages($stylist);

        $this->closeModal();
        $this->dispatch('toast', ['type' => 'success', 'message' => __('Edit Stylist: :name', ['name' => $stylist->name])]);
    }

    private function saveFeatures($stylist)
    {
        // Delete existing features
        $stylist->features()->delete();

        // Create new features
        foreach ($this->features as $feature) {
            if (!empty($feature['title'])) {
                StylistFeature::create([
                    'stylist_id' => $stylist->id,
                    'icon' => $feature['icon'] ?? '',
                    'title' => $feature['title'],
                ]);
            }
        }
    }

    private function saveServices($stylist)
    {
        // Delete existing services
        $stylist->services()->delete();

        // Create new services
        foreach ($this->services as $service) {
            if (!empty($service['title'])) {
                StylistService::create([
                    'stylist_id' => $stylist->id,
                    'title' => $service['title'],
                    'price' => $service['price'] ?? 0,
                    'available' => $service['available'] ?? true,
                ]);
            }
        }
    }

    private function saveImages($stylist)
    {
        foreach ($this->new_images as $image) {
            $path = $image->store('stylists/gallery', 'public');
            StylistImage::create([
                'stylist_id' => $stylist->id,
                'path' => $path,
            ]);
        }
    }

    private function handleImages($stylist)
    {
        // Delete removed images
        if (!empty($this->removed_images)) {
            $imagesToDelete = StylistImage::where('stylist_id', $stylist->id)
                ->whereIn('id', $this->removed_images)
                ->get();

            foreach ($imagesToDelete as $image) {
                if ($image->path && Storage::disk('public')->exists($image->path)) {
                    Storage::disk('public')->delete($image->path);
                }
                $image->delete();
            }
        }

        // Add new images
        $this->saveImages($stylist);
    }

    public function delete($id)
    {
        $stylist = Stylist::with(['features', 'images', 'services'])->findOrFail($id);

        // Delete main image
        if ($stylist->image && Storage::disk('public')->exists($stylist->image)) {
            Storage::disk('public')->delete($stylist->image);
        }

        // Delete cover image
        if ($stylist->cover && Storage::disk('public')->exists($stylist->cover)) {
            Storage::disk('public')->delete($stylist->cover);
        }

        // Delete gallery images
        foreach ($stylist->images as $image) {
            if ($image->path && Storage::disk('public')->exists($image->path)) {
                Storage::disk('public')->delete($image->path);
            }
        }

        $name = $stylist->name;
        $stylist->delete();

        $this->dispatch('toast', ['type' => 'success', 'message' => __('Delete Stylist: :name', ['name' => $name])]);
    }

    // Dynamic row management for features
    public function addFeature()
    {
        $this->features[] = ['icon' => '', 'title' => ''];
    }

    public function removeFeature($index)
    {
        if (isset($this->features[$index])) {
            unset($this->features[$index]);
            $this->features = array_values($this->features);
        }
    }

    // Dynamic row management for services
    public function addService()
    {
        $this->services[] = ['title' => '', 'price' => 0, 'available' => true];
    }

    public function removeService($index)
    {
        if (isset($this->services[$index])) {
            unset($this->services[$index]);
            $this->services = array_values($this->services);
        }
    }

    // Remove existing image
    public function removeExistingImage($index)
    {
        if (isset($this->images[$index])) {
            $this->removed_images[] = $this->images[$index]['id'];
            unset($this->images[$index]);
            $this->images = array_values($this->images);
        }
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
            'fashion_styles' => $fashion_styles,
        ]);
    }
}

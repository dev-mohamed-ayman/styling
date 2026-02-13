<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\Banner;
use Illuminate\Support\Facades\Storage;

class Banners extends Component
{
    use WithPagination, WithFileUploads;

    protected $paginationTheme = 'bootstrap';
    public $per_page = 10;
    public $search = '';
    protected $banners;

    // Modal properties
    public $is_open = false;
    public $banner_id = null;
    public $image;
    public $old_image = null;
    public $link = '';

    protected function rules()
    {
        $rules = [
            'link' => 'nullable|string|max:255',
        ];

        // For new records, image is required
        if (!$this->banner_id) {
            $rules['image'] = 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048';
        } else {
            // For update, image is not required if old_image exists
            if ($this->old_image) {
                $rules['image'] = 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048';
            } else {
                $rules['image'] = 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048';
            }
        }

        return $rules;
    }

    protected $messages = [
        'image.required' => 'The image field is required.',
        'image.image' => 'The file must be an image.',
        'image.mimes' => 'The image must be a file of type: jpeg, png, jpg, gif, svg.',
        'image.max' => 'The image size must not exceed 2MB.',
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
        $this->image = null;
        $this->old_image = null;
        $this->link = '';
        $this->banner_id = null;
    }

    public function create()
    {
        $this->openModal();
    }

    public function edit($id)
    {
        $banner = Banner::findOrFail($id);
        $this->banner_id = $id;
        $this->old_image = $banner->image;
        $this->link = $banner->link;
        $this->is_open = true;
    }

    public function store()
    {
        $this->validate();

        $imagePath = $this->image->store('banners', 'public');

        $banner = Banner::create([
            'image' => $imagePath,
            'link' => $this->link,
        ]);

        $this->closeModal();
        $this->dispatch('toast', ['type' => 'success', 'message' => __('Create Banner: :id', ['id' => $banner->id])]);
    }

    public function update()
    {
        $this->validate();

        $banner = Banner::findOrFail($this->banner_id);

        $imagePath = $this->old_image;

        if ($this->image) {
            // Delete old image if exists
            if ($banner->image && Storage::disk('public')->exists($banner->image)) {
                Storage::disk('public')->delete($banner->image);
            }
            // Store new image
            $imagePath = $this->image->store('banners', 'public');
        }

        $banner->update([
            'image' => $imagePath,
            'link' => $this->link,
        ]);

        $this->closeModal();
        $this->dispatch('toast', ['type' => 'success', 'message' => __('Edit Banner: :id', ['id' => $banner->id])]);
    }

    public function delete($id)
    {
        $banner = Banner::findOrFail($id);

        // Delete image if exists
        if ($banner->image && Storage::disk('public')->exists($banner->image)) {
            Storage::disk('public')->delete($banner->image);
        }

        $id = $banner->id;
        $banner->delete();

        $this->dispatch('toast', ['type' => 'success', 'message' => __('Delete Banner: :id', ['id' => $id])]);
    }

    public function render()
    {
        $this->banners = Banner::query()
            ->where('link', 'like', '%' . $this->search . '%')
            ->latest()->paginate($this->per_page);

        return view('livewire.dashboard.banners', [
            'banners' => $this->banners
        ]);
    }
}

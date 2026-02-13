<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\FashionStyle;
use Illuminate\Support\Facades\Storage;

class FashionStyles extends Component
{
    use WithPagination, WithFileUploads;

    protected $paginationTheme = 'bootstrap';
    public $per_page = 10;
    public $search = '';
    protected $fashion_styles;

    // Modal properties
    public $is_open = false;
    public $fashion_style_id = null;
    public $name = '';
    public $image;
    public $old_image = null;

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];
    }

    protected $messages = [
        'name.required' => 'The name field is required.',
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
        $this->name = '';
        $this->image = null;
        $this->old_image = null;
        $this->fashion_style_id = null;
    }

    public function create()
    {
        $this->openModal();
    }

    public function edit($id)
    {
        $fashion_style = FashionStyle::findOrFail($id);
        $this->fashion_style_id = $id;
        $this->name = $fashion_style->name;
        $this->old_image = $fashion_style->image;
        $this->is_open = true;
    }

    public function store()
    {
        $this->validate();

        $imagePath = $this->old_image;

        if ($this->image) {
            // Delete old image if exists
            if ($this->old_image && Storage::disk('public')->exists($this->old_image)) {
                Storage::disk('public')->delete($this->old_image);
            }
            // Store new image
            $imagePath = $this->image->store('fashion_styles', 'public');
        }

        $fashionStyle = FashionStyle::create([
            'name' => $this->name,
            'image' => $imagePath,
        ]);

        $this->closeModal();
        $this->dispatch('toast', ['type' => 'success', 'message' => __('Create Fashion Style: :name', ['name' => $fashionStyle->name])]);
    }

    public function update()
    {
        $this->validate();

        $fashion_style = FashionStyle::findOrFail($this->fashion_style_id);

        $imagePath = $this->old_image;

        if ($this->image) {
            // Delete old image if exists
            if ($fashion_style->image && Storage::disk('public')->exists($fashion_style->image)) {
                Storage::disk('public')->delete($fashion_style->image);
            }
            // Store new image
            $imagePath = $this->image->store('fashion_styles', 'public');
        }

        $fashion_style->update([
            'name' => $this->name,
            'image' => $imagePath,
        ]);

        $this->closeModal();
        $this->dispatch('toast', ['type' => 'success', 'message' => __('Edit Fashion Style: :name', ['name' => $fashion_style->name])]);
    }

    public function delete($id)
    {
        $fashion_style = FashionStyle::findOrFail($id);

        // Delete image if exists
        if ($fashion_style->image && Storage::disk('public')->exists($fashion_style->image)) {
            Storage::disk('public')->delete($fashion_style->image);
        }

        $name = $fashion_style->name;
        $fashion_style->delete();

        $this->dispatch('toast', ['type' => 'success', 'message' => __('Delete Fashion Style: :name', ['name' => $name])]);
    }

    public function render()
    {
        $this->fashion_styles = FashionStyle::query()
            ->where('name', 'like', '%' . $this->search . '%')
            ->orWhere('image', 'like', '%' . $this->search . '%')
            ->latest()->paginate($this->per_page);

        return view('livewire.dashboard.fashion_styles', [
            'fashion_styles' => $this->fashion_styles
        ]);
    }
}

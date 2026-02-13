<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\StylistFeature;
use App\Models\Stylist;

class StylistFeatures extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    public $per_page = 10;
    public $search = '';

    // Modal properties
    public $is_open = false;
    public $feature_id = null;
    public $stylist_id = null;
    public $icon = '';
    public $title = '';

    protected function rules()
    {
        return [
            'stylist_id' => 'required|exists:stylists,id',
            'icon'       => 'required|string|max:255',
            'title'      => 'required|string|max:255',
        ];
    }

    protected $messages = [
        'stylist_id.required' => 'The stylist field is required.',
        'icon.required'       => 'The icon field is required.',
        'title.required'      => 'The title field is required.',
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
        $this->feature_id = null;
        $this->stylist_id = null;
        $this->icon = '';
        $this->title = '';
    }

    public function create()
    {
        $this->openModal();
    }

    public function edit($id)
    {
        $feature = StylistFeature::findOrFail($id);
        $this->feature_id = $id;
        $this->stylist_id = $feature->stylist_id;
        $this->icon       = $feature->icon;
        $this->title      = $feature->title;
        $this->is_open    = true;
    }

    public function store()
    {
        $this->validate();

        StylistFeature::create([
            'stylist_id' => $this->stylist_id,
            'icon'       => $this->icon,
            'title'      => $this->title,
        ]);

        $this->closeModal();
        $this->dispatch('toast', ['type' => 'success', 'message' => __('Feature created successfully')]);
    }

    public function update()
    {
        $this->validate();

        $feature = StylistFeature::findOrFail($this->feature_id);

        $feature->update([
            'stylist_id' => $this->stylist_id,
            'icon'       => $this->icon,
            'title'      => $this->title,
        ]);

        $this->closeModal();
        $this->dispatch('toast', ['type' => 'success', 'message' => __('Feature updated successfully')]);
    }

    public function delete($id)
    {
        StylistFeature::findOrFail($id)->delete();

        $this->dispatch('toast', ['type' => 'success', 'message' => __('Feature deleted successfully')]);
    }

    public function render()
    {
        $features = StylistFeature::with('stylist')
            ->where('title', 'like', '%' . $this->search . '%')
            ->latest()
            ->paginate($this->per_page);

        $stylists = Stylist::all();

        return view('livewire.dashboard.stylist_features', [
            'stylist_features' => $features,
            'stylists' => $stylists,
        ]);
    }
}

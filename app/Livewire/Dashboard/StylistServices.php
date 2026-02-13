<?php

namespace App\Livewire\Dashboard;

use App\Models\Stylist;
use App\Models\StylistService;
use Livewire\Component;
use Livewire\WithPagination;

class StylistServices extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $per_page = 10;

    public $search = '';

    // Modal properties
    public $is_open = false;

    public $service_id = null;

    public $stylist_id = null;

    public $title = '';

    public $available = true;

    public $price = '';

    protected function rules()
    {
        return [
            'stylist_id' => 'required|exists:stylists,id',
            'title' => 'required|string|max:255',
            'available' => 'boolean',
            'price' => 'required|numeric|min:0',
        ];
    }

    protected $messages = [
        'stylist_id.required' => 'The stylist field is required.',
        'title.required' => 'The title field is required.',
        'price.required' => 'The price field is required.',
        'price.numeric' => 'The price must be a number.',
        'price.min' => 'The price must be at least 0.',
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
        $this->service_id = null;
        $this->stylist_id = null;
        $this->title = '';
        $this->available = true;
        $this->price = '';
    }

    public function create()
    {
        $this->openModal();
    }

    public function edit($id)
    {
        $service = StylistService::findOrFail($id);
        $this->service_id = $id;
        $this->stylist_id = $service->stylist_id;
        $this->title = $service->title;
        $this->available = $service->available;
        $this->price = $service->price;
        $this->is_open = true;
    }

    public function store()
    {
        $this->validate();

        StylistService::create([
            'stylist_id' => $this->stylist_id,
            'title' => $this->title,
            'available' => $this->available,
            'price' => $this->price,
        ]);

        $this->closeModal();
        $this->dispatch('toast', ['type' => 'success', 'message' => __('Service created successfully')]);
    }

    public function update()
    {
        $this->validate();

        $service = StylistService::findOrFail($this->service_id);

        $service->update([
            'stylist_id' => $this->stylist_id,
            'title' => $this->title,
            'available' => $this->available,
            'price' => $this->price,
        ]);

        $this->closeModal();
        $this->dispatch('toast', ['type' => 'success', 'message' => __('Service updated successfully')]);
    }

    public function delete($id)
    {
        StylistService::findOrFail($id)->delete();

        $this->dispatch('toast', ['type' => 'success', 'message' => __('Service deleted successfully')]);
    }

    public function toggleAvailability($id)
    {
        $service = StylistService::findOrFail($id);
        $service->update(['available' => ! $service->available]);

        $this->dispatch('toast', ['type' => 'success', 'message' => __('Availability updated successfully')]);
    }

    public function render()
    {
        $services = StylistService::with('stylist')
            ->where('title', 'like', '%'.$this->search.'%')
            ->orWhereHas('stylist', function ($query) {
                $query->where('name', 'like', '%'.$this->search.'%');
            })
            ->latest()
            ->paginate($this->per_page);

        $stylists = Stylist::all();

        return view('livewire.dashboard.stylist_services', [
            'stylist_services' => $services,
            'stylists' => $stylists,
        ]);
    }
}

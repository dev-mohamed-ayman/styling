<div>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">@lang('Stylist Services')</h3>
        </div>
        <hr class="m-0">
        <div class="card-body">
            <div class="row justify-content-between">
                <div class="col-md-4">
                    <input type="search" name="search" wire:model.live="search" class="form-control"
                           placeholder="@lang('Search')">
                </div>
                <div class="col-md-8">
                    <div class="rows d-flex gap-2 justify-content-end">
                        <div class="col-2">
                            <select wire:model="per_page" class="form-select">
                                <option value="10">10</option>
                                <option value="20">20</option>
                                <option value="30">30</option>
                                <option value="40">40</option>
                                <option value="50">50</option>
                            </select>
                        </div>
                        <button class="btn btn-primary" wire:click="create">Add New Stylist Service</button>
                    </div>
                </div>
            </div>
            <hr>
            <div class="table-responsive">
                <table class="table border table-hover table-striped text-center table-borderless">
                    <thead class="border-bottom">
                    <tr>
                        <th>#</th>
                        <th>Stylist</th>
                        <th>Title</th>
                        <th>Price</th>
                        <th>Available</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($stylist_services as $service)
                        <tr>
                            <td>{{ $service->id }}</td>
                            <td>{{ $service->stylist->name ?? 'N/A' }}</td>
                            <td>{{ $service->title }}</td>
                            <td>{{ number_format($service->price, 2) }}</td>
                            <td>
                                <button class="btn btn-sm {{ $service->available ? 'btn-success' : 'btn-secondary' }}"
                                        wire:click="toggleAvailability({{ $service->id }})">
                                    {{ $service->available ? __('Yes') : __('No') }}
                                </button>
                            </td>
                            <td>
                                <div class="d-flex gap-2 justify-content-center">
                                    <button class="btn btn-sm btn-info" wire:click="edit({{ $service->id }})">
                                        <i class="icon-base ti tabler-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger" wire:click="delete({{ $service->id }})"
                                            onclick="confirm('Are you sure you want to delete this service?') || event.stopImmediatePropagation()">
                                        <i class="icon-base ti tabler-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <hr>
        <div class="card-footer">{{ $stylist_services->links() }}</div>
    </div>

    <!-- Modal -->
    @if($is_open)
        <div class="modal fade show" style="display: block;" id="stylistServiceModal" tabindex="-1" aria-labelledby="stylistServiceModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="stylistServiceModalLabel">
                            {{ $service_id ? __('Edit Service') : __('Add New Service') }}
                        </h5>
                        <button type="button" class="btn-close" wire:click="closeModal" aria-label="Close"></button>
                    </div>
                    <form wire:submit.prevent="{{ $service_id ? 'update' : 'store' }}">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="stylist_id" class="form-label">Stylist <span class="text-danger">*</span></label>
                                <select id="stylist_id" class="form-select" wire:model="stylist_id">
                                    <option value="">Select Stylist</option>
                                    @foreach($stylists as $stylist)
                                        <option value="{{ $stylist->id }}">{{ $stylist->name }}</option>
                                    @endforeach
                                </select>
                                @error('stylist_id')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="title" wire:model="title"
                                       placeholder="Enter service title">
                                @error('title')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="price" class="form-label">Price <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" min="0" class="form-control" id="price" wire:model="price"
                                       placeholder="Enter service price">
                                @error('price')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="available" wire:model="available">
                                    <label class="form-check-label" for="available">Available</label>
                                </div>
                                @error('available')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" wire:click="closeModal">Close</button>
                            <button type="submit" class="btn btn-primary">
                                {{ $service_id ? __('Update') : __('Save') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show"></div>
    @endif
</div>

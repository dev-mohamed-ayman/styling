<div>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">@lang('Stylists')</h3>
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
                        <button class="btn btn-primary" wire:click="create">@lang('Add New Stylist')</button>
                    </div>
                </div>
            </div>
            <hr>
            <div class="table-responsive">
                <table class="table border table-hover table-striped text-center table-borderless">
                    <thead class="border-bottom">
                    <tr>
                        <th>#</th>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Bio</th>
                        <th>Price</th>
                        <th>Features</th>
                        <th>Images</th>
                        <th>Services</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($stylists as $stylist)
                        <tr>
                            <td>{{ $stylist->id }}</td>
                            <td>
                                @if($stylist->image)
                                    <img src="{{ $stylist->image_url }}" alt="{{ $stylist->name }}"
                                         class="rounded" style="width: 50px; height: 50px; object-fit: cover;">
                                @else
                                    <span class="badge bg-secondary">No Image</span>
                                @endif
                            </td>
                            <td>{{ $stylist->name }}</td>
                            <td>{{ Str::limit($stylist->bio, 30) }}</td>
                            <td>${{ number_format($stylist->price, 2) }}</td>
                            <td>
                                <span class="badge bg-info">{{ $stylist->features->count() }}</span>
                            </td>
                            <td>
                                <span class="badge bg-warning">{{ $stylist->images->count() }}</span>
                            </td>
                            <td>
                                <span class="badge bg-success">{{ $stylist->services->count() }}</span>
                            </td>
                            <td>
                                <div class="d-flex gap-2 justify-content-center">
                                    <button class="btn btn-sm btn-info" wire:click="edit({{ $stylist->id }})">
                                        <i class="icon-base ti tabler-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger" wire:click="delete({{ $stylist->id }})"
                                            onclick="confirm('Are you sure you want to delete this stylist?') || event.stopImmediatePropagation()">
                                        <i class="icon-base ti tabler-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center">No stylists found.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <hr>
        <div class="card-footer">{{ $stylists->links() }}</div>
    </div>

    <!-- Modal -->
    @if($is_open)
        <div class="modal fade show" style="display: block;" id="stylistModal" tabindex="-1" aria-labelledby="stylistModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="stylistModalLabel">
                            {{ $stylist_id ? __('Edit Stylist') : __('Add New Stylist') }}
                        </h5>
                        <button type="button" class="btn-close" wire:click="$set('is_open', false)" aria-label="Close"></button>
                    </div>
                    <form wire:submit.prevent="{{ $stylist_id ? 'update' : 'store' }}">
                        <div class="modal-body">
                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs" id="stylistTabs" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="basic-tab" data-bs-toggle="tab" data-bs-target="#basic" type="button" role="tab">
                                        Basic Info
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="features-tab" data-bs-toggle="tab" data-bs-target="#features" type="button" role="tab">
                                        Features
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="images-tab" data-bs-toggle="tab" data-bs-target="#images" type="button" role="tab">
                                        Images
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="services-tab" data-bs-toggle="tab" data-bs-target="#services" type="button" role="tab">
                                        Services
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="styles-tab" data-bs-toggle="tab" data-bs-target="#styles" type="button" role="tab">
                                        Fashion Styles
                                    </button>
                                </li>
                            </ul>

                            <!-- Tab panes -->
                            <div class="tab-content p-3 border border-top-0 rounded-bottom">
                                <!-- Basic Info Tab -->
                                <div class="tab-pane fade show active" id="basic" role="tabpanel" aria-labelledby="basic-tab">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="name" wire:model="name"
                                                       placeholder="Enter stylist name">
                                                @error('name')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="price" class="form-label">Price</label>
                                                <input type="number" step="0.01" class="form-control" id="price" wire:model="price"
                                                       placeholder="Enter price">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="image" class="form-label">Profile Image</label>
                                                <input type="file" class="form-control" id="image" wire:model="image"
                                                       accept="image/*">
                                                @error('image')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                                @if($old_image)
                                                    <div class="mt-2">
                                                        <p class="mb-1">Current Image:</p>
                                                        <img src="{{ asset('storage/' . $old_image) }}" alt="Current Image"
                                                             class="rounded" style="width: 100px; height: 100px; object-fit: cover;">
                                                    </div>
                                                @endif
                                                @if($image)
                                                    <div class="mt-2">
                                                        <p class="mb-1">New Image Preview:</p>
                                                        <img src="{{ $image->temporaryUrl() }}" alt="New Image Preview"
                                                             class="rounded" style="width: 100px; height: 100px; object-fit: cover;">
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="cover" class="form-label">Cover Image</label>
                                                <input type="file" class="form-control" id="cover" wire:model="cover"
                                                       accept="image/*">
                                                @error('cover')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                                @if($old_cover)
                                                    <div class="mt-2">
                                                        <p class="mb-1">Current Cover:</p>
                                                        <img src="{{ asset('storage/' . $old_cover) }}" alt="Current Cover"
                                                             class="rounded" style="width: 100px; height: 100px; object-fit: cover;">
                                                    </div>
                                                @endif
                                                @if($cover)
                                                    <div class="mt-2">
                                                        <p class="mb-1">New Cover Preview:</p>
                                                        <img src="{{ $cover->temporaryUrl() }}" alt="New Cover Preview"
                                                             class="rounded" style="width: 100px; height: 100px; object-fit: cover;">
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="bio" class="form-label">Bio</label>
                                        <textarea class="form-control" id="bio" wire:model="bio" rows="3"
                                                  placeholder="Enter short bio"></textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label for="about" class="form-label">About</label>
                                        <textarea class="form-control" id="about" wire:model="about" rows="5"
                                                  placeholder="Enter detailed about information"></textarea>
                                    </div>
                                </div>

                                <!-- Features Tab -->
                                <div class="tab-pane fade" id="features" role="tabpanel" aria-labelledby="features-tab">
                                    <div class="d-flex justify-content-end mb-2">
                                        <button type="button" class="btn btn-sm btn-success" wire:click="addFeature">
                                            <i class="icon-base ti tabler-plus"></i> Add Feature
                                        </button>
                                    </div>
                                    @foreach($features as $index => $feature)
                                        <div class="row mb-2 align-items-end">

                                            <div class="mb-3">
                                                <label for="new_images" class="form-label"> Images</label>
                                                <input type="file" class="form-control" id="new_images" wire:model="features.{{ $index }}.images"
                                                       accept="image/*" multiple>
                                                <small class="text-muted">You can select multiple images at once</small>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Title</label>
                                                <input type="text" class="form-control" wire:model="features.{{ $index }}.title"
                                                       placeholder="Feature title">
                                            </div>
                                            <div class="col-md-2">
                                                <button type="button" class="btn btn-sm btn-danger" wire:click="removeFeature({{ $index }})">
                                                    <i class="icon-base ti tabler-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                    @if(count($features) == 0)
                                        <div class="text-center text-muted py-3">
                                            No features added yet. Click "Add Feature" to add one.
                                        </div>
                                    @endif
                                </div>

                                <!-- Images Tab -->
                                <div class="tab-pane fade" id="images" role="tabpanel" aria-labelledby="images-tab">
                                    <div class="mb-3">
                                        <label for="new_images" class="form-label">Add New Images</label>
                                        <input type="file" class="form-control" id="new_images" wire:model="new_images"
                                               accept="image/*" multiple>
                                        <small class="text-muted">You can select multiple images at once</small>
                                    </div>

                                    @if(count($new_images) > 0)
                                        <div class="mb-3">
                                            <p class="mb-1">New Images Preview:</p>
                                            <div class="d-flex flex-wrap gap-2">
                                                @foreach($new_images as $index => $image)
                                                    <div class="position-relative">
                                                        <img src="{{ $image->temporaryUrl() }}" alt="New Image"
                                                             class="rounded" style="width: 80px; height: 80px; object-fit: cover;">
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif

                                    @if(count($images) > 0)
                                        <div class="mb-3">
                                            <p class="mb-1">Existing Images:</p>
                                            <div class="d-flex flex-wrap gap-2">
                                                @foreach($images as $index => $image)
                                                    <div class="position-relative">
                                                        <img src="{{ asset('storage/' . $image['path']) }}" alt="Existing Image"
                                                             class="rounded" style="width: 80px; height: 80px; object-fit: cover;">
                                                        <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0"
                                                                wire:click="removeExistingImage({{ $index }})" style="padding: 2px 6px;">
                                                            <i class="icon-base ti tabler-x" style="font-size: 10px;"></i>
                                                        </button>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <!-- Services Tab -->
                                <div class="tab-pane fade" id="services" role="tabpanel" aria-labelledby="services-tab">
                                    <div class="d-flex justify-content-end mb-2">
                                        <button type="button" class="btn btn-sm btn-success" wire:click="addService">
                                            <i class="icon-base ti tabler-plus"></i> Add Service
                                        </button>
                                    </div>
                                    @foreach($services as $index => $service)
                                        <div class="row mb-2 align-items-end">
                                            <div class="col-md-4">
                                                <label class="form-label">Title</label>
                                                <input type="text" class="form-control" wire:model="services.{{ $index }}.title"
                                                       placeholder="Service title">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">Price</label>
                                                <input type="number" step="0.01" class="form-control" wire:model="services.{{ $index }}.price"
                                                       placeholder="Price">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">Available</label>
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" wire:model="services.{{ $index }}.available"
                                                           id="service_available_{{ $index }}">
                                                    <label class="form-check-label" for="service_available_{{ $index }}">Available</label>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <button type="button" class="btn btn-sm btn-danger" wire:click="removeService({{ $index }})">
                                                    <i class="icon-base ti tabler-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                    @if(count($services) == 0)
                                        <div class="text-center text-muted py-3">
                                            No services added yet. Click "Add Service" to add one.
                                        </div>
                                    @endif
                                </div>

                                <!-- Fashion Styles Tab -->
                                <div class="tab-pane fade" id="styles" role="tabpanel" aria-labelledby="styles-tab">
                                    <div class="mb-3">
                                        <label class="form-label">Select Fashion Styles</label>
                                        <div class="row">
                                            @forelse($fashion_styles as $style)
                                                <div class="col-md-4 mb-2">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox"
                                                               wire:model="selected_fashion_styles"
                                                               value="{{ $style->id }}"
                                                               id="style_{{ $style->id }}">
                                                        <label class="form-check-label" for="style_{{ $style->id }}">
                                                            {{ $style->name }}
                                                        </label>
                                                    </div>
                                                </div>
                                            @empty
                                                <div class="col-12">
                                                    <p class="text-muted">No fashion styles available.</p>
                                                </div>
                                            @endforelse
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" wire:click="$set('is_open', false)">Close</button>
                            <button type="submit" class="btn btn-primary">
                                {{ $stylist_id ? __('Update') : __('Save') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show"></div>
    @endif
</div>

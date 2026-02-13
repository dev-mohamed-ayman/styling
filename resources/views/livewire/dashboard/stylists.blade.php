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
                        <th>Name</th>
                        <th>Image</th>
                        <th>Bio</th>
                        <th>Price</th>
                        <th>Rating</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($stylists as $stylist)
                        <tr>
                            <td>{{$stylist->id}}</td>
                            <td>{{$stylist->name}}</td>
                            <td>
                                @if($stylist->image)
                                    <img src="{{$stylist->image_url}}" alt="{{$stylist->name}}"
                                         class="rounded" style="width: 50px; height: 50px; object-fit: cover;">
                                @else
                                    <span class="badge bg-secondary">No Image</span>
                                @endif
                            </td>
                            <td>{{ Str::limit($stylist->bio, 50) }}</td>
                            <td>${{ number_format($stylist->price, 2) }}</td>
                            <td>
                                @if($stylist->avg_rating > 0)
                                    <span class="badge bg-warning">
                                        <i class="icon-base ti tabler-star filled"></i>
                                        {{ number_format($stylist->avg_rating, 1) }}
                                        ({{ $stylist->reviews_count }})
                                    </span>
                                @else
                                    <span class="badge bg-secondary">No ratings</span>
                                @endif
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
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <hr>
        <div class="card-footer">{{$stylists->links()}}</div>
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
                        <button type="button" class="btn-close" wire:click="closeModal" aria-label="Close"></button>
                    </div>
                    <form wire:submit.prevent="{{ $stylist_id ? 'update' : 'store' }}">
                        <div class="modal-body">
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
                                        <label for="price" class="form-label">Price <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" id="price" wire:model="price"
                                               placeholder="Enter price" step="0.01" min="0">
                                        @error('price')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="image" class="form-label">Image</label>
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
                                <textarea class="form-control" id="bio" wire:model="bio"
                                          placeholder="Enter short bio" rows="2"></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="about" class="form-label">About</label>
                                <textarea class="form-control" id="about" wire:model="about"
                                          placeholder="Enter about details" rows="4"></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="fashion_styles" class="form-label">Fashion Styles</label>
                                <select class="form-select" id="fashion_styles" wire:model="selected_fashion_styles" multiple>
                                    @foreach($fashion_styles as $fashionStyle)
                                        <option value="{{ $fashionStyle->id }}">{{ $fashionStyle->name }}</option>
                                    @endforeach
                                </select>
                                <small class="text-muted">Hold Ctrl/Cmd to select multiple</small>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" wire:click="closeModal">@lang('Close')</button>
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

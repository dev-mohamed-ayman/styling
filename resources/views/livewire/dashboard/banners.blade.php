<div>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">@lang('Banners')</h3>
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
                        <button class="btn btn-primary" wire:click="create">Add New Banner</button>
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
                        <th>Link</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($banners as $banner)
                        <tr>
                            <td>{{$banner->id}}</td>
                            <td>
                                @if($banner->image)
                                    <img src="{{$banner->image}}" alt="Banner"
                                         class="rounded" style="width: 80px; height; object-fit:: 50px cover;">
                                @else
                                    <span class="badge bg-secondary">No Image</span>
                                @endif
                            </td>
                            <td>
                                @if($banner->link)
                                    <a href="{{$banner->link}}" target="_blank" class="btn btn-sm btn-outline-primary">
                                        <i class="icon-base ti tabler-link"></i>
                                    </a>
                                @else
                                    <span class="badge bg-secondary">No Link</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex gap-2 justify-content-center">
                                    <button class="btn btn-sm btn-info" wire:click="edit({{ $banner->id }})">
                                        <i class="icon-base ti tabler-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger" wire:click="delete({{ $banner->id }})"
                                            onclick="confirm('Are you sure you want to delete this banner?') || event.stopImmediatePropagation()">
                                        <i class="icon-base ti tabler-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">No banners found.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <hr>
        <div class="card-footer">{{$banners->links()}}</div>
    </div>

    <!-- Modal -->
    @if($is_open)
        <div class="modal fade show" style="display: block;" id="bannerModal" tabindex="-1" aria-labelledby="bannerModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="bannerModalLabel">
                            {{ $banner_id ? __('Edit Banner') : __('Add New Banner') }}
                        </h5>
                        <button type="button" class="btn-close" wire:click="$set('is_open', false)" aria-label="Close"></button>
                    </div>
                    <form wire:submit.prevent="{{ $banner_id ? 'update' : 'store' }}">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="image" class="form-label">Image <span class="text-danger">*</span></label>
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
                            <div class="mb-3">
                                <label for="link" class="form-label">Link</label>
                                <input type="text" class="form-control" id="link" wire:model="link"
                                       placeholder="Enter banner link (optional)">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" wire:click="$set('is_open', false)">Close</button>
                            <button type="submit" class="btn btn-primary">
                                {{ $banner_id ? __('Update') : __('Save') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show"></div>
    @endif
</div>

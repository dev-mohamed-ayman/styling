<div>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">@lang('Stylist Images')</h3>
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
                        <button class="btn btn-primary" wire:click="create">Add New Stylist Image</button>
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
                        <th>Image</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($stylist_images as $stylistImage)
                        <tr>
                            <td>{{ $stylistImage->id }}</td>
                            <td>{{ $stylistImage->stylist->name ?? 'N/A' }}</td>
                            <td>
                                <img src="{{ $stylistImage->path }}" alt="Stylist Image" class="img-thumbnail" style="max-width: 100px; max-height: 100px;">
                            </td>
                            <td>
                                <div class="d-flex gap-2 justify-content-center">
                                    <button class="btn btn-sm btn-info" wire:click="edit({{ $stylistImage->id }})">
                                        <i class="icon-base ti tabler-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger" wire:click="delete({{ $stylistImage->id }})"
                                            onclick="confirm('Are you sure you want to delete this image?') || event.stopImmediatePropagation()">
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
        <div class="card-footer">{{ $stylist_images->links() }}</div>
    </div>

    <!-- Modal -->
    @if($is_open)
        <div class="modal fade show" style="display: block;" id="stylistImageModal" tabindex="-1" aria-labelledby="stylistImageModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="stylistImageModalLabel">
                            {{ $image_id ? __('Edit Image') : __('Add New Image') }}
                        </h5>
                        <button type="button" class="btn-close" wire:click="closeModal" aria-label="Close"></button>
                    </div>
                    <form wire:submit.prevent="{{ $image_id ? 'update' : 'store' }}" enctype="multipart/form-data">
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
                                <label for="image" class="form-label">Image <span class="text-danger">*</span></label>
                                <input type="file" class="form-control" id="image" wire:model="image" accept="image/*">
                                <div wire:loading wire:target="image" class="text-muted mt-1">Uploading...</div>
                                @error('image')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                                @if($image)
                                    <div class="mt-2">
                                        <img src="{{ $image->temporaryUrl() }}" alt="Preview" class="img-thumbnail" style="max-width: 150px; max-height: 150px;">
                                    </div>
                                @elseif($old_image)
                                    <div class="mt-2">
                                        <img src="{{ asset('storage/' . $old_image) }}" alt="Current Image" class="img-thumbnail" style="max-width: 150px; max-height: 150px;">
                                        <p class="text-muted small mt-1">Current image</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" wire:click="closeModal">Close</button>
                            <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                                <span wire:loading wire:target="{{ $image_id ? 'update' : 'store' }}" class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                                {{ $image_id ? __('Update') : __('Save') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show"></div>
    @endif
</div>

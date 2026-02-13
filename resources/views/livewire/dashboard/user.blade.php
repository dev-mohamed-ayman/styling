<div>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">@lang('Users')</h3>
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
                        <button class="btn btn-primary" wire:click="create">Add New User</button>
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
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Gender</th>
                        <th>Block</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>
                                <img src="{{ $user->image }}" alt="{{ $user->name }}" class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover;">
                            </td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email ?? 'N/A' }}</td>
                            <td>{{ $user->phone ?? 'N/A' }}</td>
                            <td>{{ $user->gender ? ucfirst($user->gender) : 'N/A' }}</td>
                            <td>
                                <label class="switch switch-primary">
                                    <input type="checkbox" class="switch-input"
                                           {{ $user->is_block ? 'checked' : '' }} wire:click="block({{ $user->id }})"/>
                                    <span class="switch-toggle-slider">
                                      <span class="switch-on">
                                        <i class="icon-base ti tabler-check"></i>
                                      </span>
                                      <span class="switch-off">
                                        <i class="icon-base ti tabler-x"></i>
                                      </span>
                                    </span>
                                </label>
                            </td>
                            <td>
                                <div class="d-flex gap-2 justify-content-center">
                                    <button class="btn btn-sm btn-info" wire:click="edit({{ $user->id }})">
                                        <i class="icon-base ti tabler-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger" wire:click="delete({{ $user->id }})"
                                            onclick="confirm('Are you sure you want to delete this user?') || event.stopImmediatePropagation()">
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
        <div class="card-footer">{{ $users->links() }}</div>
    </div>

    <!-- Modal -->
    @if($is_open)
        <div class="modal fade show" style="display: block;" id="userModal" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="userModalLabel">
                            {{ $user_id ? __('Edit User') : __('Add New User') }}
                        </h5>
                        <button type="button" class="btn-close" wire:click="closeModal" aria-label="Close"></button>
                    </div>
                    <form wire:submit.prevent="{{ $user_id ? 'update' : 'store' }}" enctype="multipart/form-data">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="name" wire:model="name"
                                       placeholder="Enter user name">
                                @error('name')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" wire:model="email"
                                       placeholder="Enter email address">
                                @error('email')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="phone" class="form-label">Phone</label>
                                <input type="text" class="form-control" id="phone" wire:model="phone"
                                       placeholder="Enter phone number">
                                @error('phone')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">
                                    Password
                                    @if(!$user_id)
                                        <span class="text-danger">*</span>
                                    @else
                                        <small class="text-muted">(Leave empty to keep current)</small>
                                    @endif
                                </label>
                                <input type="password" class="form-control" id="password" wire:model="password"
                                       placeholder="Enter password">
                                @error('password')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="gender" class="form-label">Gender</label>
                                <select id="gender" class="form-select" wire:model="gender">
                                    <option value="">Select Gender</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                </select>
                                @error('gender')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="image" class="form-label">Image</label>
                                <input type="file" class="form-control" id="image" wire:model="image" accept="image/*">
                                <div wire:loading wire:target="image" class="text-muted mt-1">Uploading...</div>
                                @error('image')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                                @if($image)
                                    <div class="mt-2">
                                        <img src="{{ $image->temporaryUrl() }}" alt="Preview" class="img-thumbnail" style="max-width: 100px; max-height: 100px;">
                                    </div>
                                @elseif($old_image)
                                    <div class="mt-2">
                                        <img src="{{ asset('storage/' . $old_image) }}" alt="Current Image" class="img-thumbnail" style="max-width: 100px; max-height: 100px;">
                                        <p class="text-muted small mt-1">Current image</p>
                                    </div>
                                @endif
                            </div>
                            <div class="mb-3">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="is_block" wire:model="is_block">
                                    <label class="form-check-label" for="is_block">Blocked</label>
                                </div>
                                @error('is_block')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" wire:click="closeModal">Close</button>
                            <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                                <span wire:loading wire:target="{{ $user_id ? 'update' : 'store' }}" class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                                {{ $user_id ? __('Update') : __('Save') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show"></div>
    @endif
</div>

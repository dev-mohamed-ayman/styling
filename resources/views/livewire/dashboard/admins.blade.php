<div>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">@lang('Admins')</h3>
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
                        <button class="btn btn-primary" wire:click="create">Add New Admin</button>
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
                        <th>Username</th>
                        <th>Role</th>
                        <th>Active</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($admins as $admin)
                        <tr>
                            <td>{{ $admin->id }}</td>
                            <td>
                                <img src="{{ $admin->image }}" alt="{{ $admin->name }}" class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover;">
                            </td>
                            <td>{{ $admin->name }}</td>
                            <td>{{ $admin->email }}</td>
                            <td>{{ $admin->username }}</td>
                            <td>
                                @if($admin->is_super_admin)
                                    <span class="badge bg-label-danger">Super Admin</span>
                                @else
                                    <span class="badge bg-label-info">{{ ucfirst($admin->role) }}</span>
                                @endif
                            </td>
                            <td>
                                <label class="switch switch-primary">
                                    <input type="checkbox" class="switch-input"
                                           {{ $admin->is_active ? 'checked' : '' }} wire:click="toggleActive({{ $admin->id }})"/>
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
                                    <button class="btn btn-sm btn-info" wire:click="edit({{ $admin->id }})"
                                            @if($admin->id === auth('admin')->id()) disabled @endif>
                                        <i class="icon-base ti tabler-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger" wire:click="delete({{ $admin->id }})"
                                            onclick="confirm('Are you sure you want to delete this admin?') || event.stopImmediatePropagation()"
                                            @if($admin->id === auth('admin')->id()) disabled @endif>
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
        <div class="card-footer">{{ $admins->links() }}</div>
    </div>

    <!-- Modal -->
    @if($is_open)
        <div class="modal fade show" style="display: block;" id="adminModal" tabindex="-1" aria-labelledby="adminModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="adminModalLabel">
                            {{ $admin_id ? __('Edit Admin') : __('Add New Admin') }}
                        </h5>
                        <button type="button" class="btn-close" wire:click="closeModal" aria-label="Close"></button>
                    </div>
                    <form wire:submit.prevent="{{ $admin_id ? 'update' : 'store' }}">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="name" wire:model="name"
                                       placeholder="Enter admin name">
                                @error('name')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="email" wire:model="email"
                                       placeholder="Enter email address">
                                @error('email')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="username" class="form-label">Username <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="username" wire:model="username"
                                       placeholder="Enter username">
                                @error('username')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">
                                    Password
                                    @if(!$admin_id)
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
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="is_active" wire:model="is_active">
                                    <label class="form-check-label" for="is_active">Active</label>
                                </div>
                                @error('is_active')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            
                            @if(auth('admin')->user()->hasRole('Super Admin'))
                            <div class="mb-3">
                                <label for="role" class="form-label">Role</label>
                                <select class="form-control" id="role" wire:model="role">
                                    <option value="admin">Admin</option>
                                    <option value="moderator">Moderator</option>
                                </select>
                                @error('role')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            
                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="is_super_admin" wire:model="is_super_admin">
                                <label class="form-check-label" for="is_super_admin">Super Admin</label>
                            </div>
                            @error('is_super_admin')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                            @endif
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" wire:click="closeModal">Close</button>
                            <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                                <span wire:loading wire:target="{{ $admin_id ? 'update' : 'store' }}" class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                                {{ $admin_id ? __('Update') : __('Save') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show"></div>
    @endif
</div>

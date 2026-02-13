<div>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">@lang('Roles Management')</h3>
        </div>
        <hr class="m-0">
        <div class="card-body">
            <div class="row justify-content-between">
                <div class="col-md-4">
                    <input type="search" name="search" wire:model.live="search" class="form-control"
                           placeholder="@lang('Search roles')">
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
                        @can('create roles')
                            <button class="btn btn-primary" wire:click="create">Add New Role</button>
                        @endcan
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
                        <th>Guard</th>
                        <th>Permissions</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($roles as $role)
                        <tr>
                            <td>{{ $role->id }}</td>
                            <td>
                                <span class="badge bg-label-primary">{{ $role->name }}</span>
                                @if(in_array($role->name, ['Super Admin']))
                                    <span class="badge bg-label-danger">System</span>
                                @endif
                            </td>
                            <td>{{ $role->guard_name }}</td>
                            <td>
                                <span class="badge bg-label-info">{{ $role->permissions->count() }} permissions</span>
                            </td>
                            <td>
                                <div class="d-flex gap-2 justify-content-center">
                                    @can('assign permissions')
                                        <button class="btn btn-sm btn-success" wire:click="openPermissionModal({{ $role->id }})"
                                                title="Manage Permissions">
                                            <i class="icon-base ti tabler-key"></i>
                                        </button>
                                    @endcan
                                    @can('edit roles')
                                        <button class="btn btn-sm btn-info" wire:click="edit({{ $role->id }})"
                                                @if(in_array($role->name, ['Super Admin'])) disabled @endif>
                                            <i class="icon-base ti tabler-edit"></i>
                                        </button>
                                    @endcan
                                    @can('delete roles')
                                        <button class="btn btn-sm btn-danger" wire:click="delete({{ $role->id }})"
                                                onclick="confirm('Are you sure you want to delete this role?') || event.stopImmediatePropagation()"
                                                @if(in_array($role->name, ['Super Admin', 'Admin', 'User'])) disabled @endif>
                                            <i class="icon-base ti tabler-trash"></i>
                                        </button>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <hr>
        <div class="card-footer">{{ $roles->links() }}</div>
    </div>

    <!-- Role Modal -->
    @if($is_open)
        <div class="modal fade show" style="display: block;" id="roleModal" tabindex="-1" aria-labelledby="roleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="roleModalLabel">
                            {{ $role_id ? __('Edit Role') : __('Add New Role') }}
                        </h5>
                        <button type="button" class="btn-close" wire:click="closeModal" aria-label="Close"></button>
                    </div>
                    <form wire:submit.prevent="{{ $role_id ? 'update' : 'store' }}">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="name" class="form-label">Role Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="name" wire:model="name"
                                       placeholder="Enter role name">
                                @error('name')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="guard_name" class="form-label">Guard <span class="text-danger">*</span></label>
                                <select id="guard_name" class="form-select" wire:model="guard_name">
                                    <option value="admin">Admin</option>
                                    <option value="web">Web</option>
                                </select>
                                @error('guard_name')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" wire:click="closeModal">Close</button>
                            <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                                <span wire:loading wire:target="{{ $role_id ? 'update' : 'store' }}" class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                                {{ $role_id ? __('Update') : __('Save') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show"></div>
    @endif

    <!-- Permissions Modal -->
    @if($is_permission_modal_open)
        <div class="modal fade show" style="display: block;" id="permissionModal" tabindex="-1" aria-labelledby="permissionModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="permissionModalLabel">
                            {{ __('Manage Permissions for') }}: {{ $name }}
                        </h5>
                        <button type="button" class="btn-close" wire:click="closePermissionModal" aria-label="Close"></button>
                    </div>
                    <form wire:submit.prevent="updatePermissions">
                        <div class="modal-body" style="max-height: 60vh; overflow-y: auto;">
                            @foreach($permissions as $group => $groupPermissions)
                                <div class="mb-4">
                                    <h6 class="text-capitalize fw-bold border-bottom pb-2">{{ $group }}</h6>
                                    <div class="row">
                                        @foreach($groupPermissions as $permission)
                                            <div class="col-md-4 mb-2">
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input"
                                                           id="permission_{{ $permission->id }}"
                                                           value="{{ $permission->id }}"
                                                           wire:model="selected_permissions">
                                                    <label class="form-check-label" for="permission_{{ $permission->id }}">
                                                        {{ ucfirst(str_replace(['_', $group], ['', ''], $permission->name)) }}
                                                    </label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" wire:click="closePermissionModal">Close</button>
                            <button type="submit" class="btn btn-success" wire:loading.attr="disabled">
                                <span wire:loading wire:target="updatePermissions" class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                                {{ __('Update Permissions') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show"></div>
    @endif
</div>

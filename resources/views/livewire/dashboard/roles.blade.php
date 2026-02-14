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
                        <th>@lang('Name')</th>
                        <th>@lang('Guard')</th>
                        <th>@lang('Permissions')</th>
                        <th>@lang('Actions')</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($roles as $role)
                        <tr>
                            <td>{{ $role->id }}</td>
                            <td>{{ $role->name }}</td>
                            <td><span class="badge bg-label-info">{{ $role->guard_name }}</span></td>
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
                                                @if(in_array($role->name, ['Super Admin', 'Admin', 'Moderator'])) disabled @endif>
                                            <i class="icon-base ti tabler-trash"></i>
                                        </button>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">@lang('No roles found')</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <hr>
        <div class="card-footer">{{ $roles->links() }}</div>
    </div>

    <!-- Modal -->
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
                                <label for="name" class="form-label">@lang('Name') <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="name" wire:model="name"
                                       placeholder="@lang('Enter role name')">
                                @error('name')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="guard_name" class="form-label">@lang('Guard Name') <span class="text-danger">*</span></label>
                                <select class="form-control" id="guard_name" wire:model="guard_name">
                                    <option value="admin">@lang('Admin')</option>
                                    <option value="web">@lang('Web')</option>
                                </select>
                                @error('guard_name')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" wire:click="closeModal">@lang('Close')</button>
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
                            @lang('Manage Permissions for'): {{ $role_id ? \Spatie\Permission\Models\Role::find($role_id)->name : '' }}
                        </h5>
                        <button type="button" class="btn-close" wire:click="closePermissionModal" aria-label="Close"></button>
                    </div>
                    <form wire:submit.prevent="updatePermissions">
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label class="form-label">@lang('Select Permissions')</label>
                                        <div class="row">
                                            @php
                                                $permissionsGrouped = \Spatie\Permission\Models\Permission::where('guard_name', 'admin')
                                                    ->orderBy('name')
                                                    ->get()
                                                    ->groupBy(function ($permission) {
                                                        $parts = explode(' ', $permission->name);
                                                        return end($parts);
                                                    });
                                            @endphp
                                            
                                            @foreach($permissionsGrouped as $resource => $permissions)
                                                <div class="col-md-6 mb-3">
                                                    <div class="card">
                                                        <div class="card-header">
                                                            <h6 class="mb-0 text-capitalize">{{ $resource }}</h6>
                                                        </div>
                                                        <div class="card-body">
                                                            @foreach($permissions as $permission)
                                                                <div class="form-check mb-2">
                                                                    <input type="checkbox" class="form-check-input" id="perm_{{ $permission->id }}" 
                                                                           wire:model="selected_permissions" value="{{ $permission->id }}">
                                                                    <label class="form-check-label text-capitalize" for="perm_{{ $permission->id }}">
                                                                        {{ str_replace(' '.$resource, '', $permission->name) }} {{ $resource }}
                                                                    </label>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" wire:click="closePermissionModal">@lang('Close')</button>
                            <button type="submit" class="btn btn-success" wire:loading.attr="disabled">
                                <span wire:loading wire:target="updatePermissions" class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                                @lang('Update Permissions')
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show"></div>
    @endif
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
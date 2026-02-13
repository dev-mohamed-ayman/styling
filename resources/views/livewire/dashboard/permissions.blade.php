<div>
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">@lang('Permissions')</h5>
            <button type="button" class="btn btn-primary" wire:click="create">
                <i class="ti ti-plus me-1"></i>@lang('Add Permission')
            </button>
        </div>
        <div class="card-body">
            <!-- Search -->
            <div class="row mb-3">
                <div class="col-md-4">
                    <input type="text" class="form-control" placeholder="@lang('Search...')" wire:model.live="search">
                </div>
            </div>

            <!-- Table -->
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>@lang('ID')</th>
                            <th>@lang('Name')</th>
                            <th>@lang('Guard')</th>
                            <th>@lang('Actions')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($permissions as $permission)
                        <tr>
                            <td>{{ $permission->id }}</td>
                            <td>{{ $permission->name }}</td>
                            <td>
                                <span class="badge bg-label-{{ $permission->guard_name === 'admin' ? 'primary' : 'info' }}">
                                    {{ $permission->guard_name }}
                                </span>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-info" wire:click="edit({{ $permission->id }})" title="@lang('Edit')">
                                    <i class="ti ti-pencil"></i>
                                </button>
                                @if(!in_array($permission->name, ['view dashboard']))
                                <button class="btn btn-sm btn-danger" wire:click="delete({{ $permission->id }})" wire:confirm="@lang('Are you sure?')" title="@lang('Delete')">
                                    <i class="ti ti-trash"></i>
                                </button>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center">@lang('No permissions found')</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-3">
                {{ $permissions->links() }}
            </div>
        </div>
    </div>

    <!-- Modal -->
    @if($is_open)
    <div class="modal show d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.5);">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ $permission_id ? __('Edit Permission') : __('Add Permission') }}</h5>
                    <button type="button" class="btn-close" wire:click="closeModal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">@lang('Permission Name')</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" wire:model="name" placeholder="@lang('e.g., view users')">
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">@lang('Guard Name')</label>
                        <select class="form-select @error('guard_name') is-invalid @enderror" wire:model="guard_name">
                            <option value="admin">admin</option>
                            <option value="web">web</option>
                        </select>
                        @error('guard_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click="closeModal">@lang('Cancel')</button>
                    <button type="button" class="btn btn-primary" wire:click="{{ $permission_id ? 'update' : 'store' }}">
                        {{ $permission_id ? __('Update') : __('Save') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

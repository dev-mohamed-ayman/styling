<div>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">@lang('Assign Roles to Admins')</h3>
        </div>
        <hr class="m-0">
        <div class="card-body">
            <div class="row justify-content-between">
                <div class="col-md-4">
                    <input type="search" name="search" wire:model.live="search" class="form-control"
                           placeholder="@lang('Search admins')">
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
                        <th>@lang('Email')</th>
                        <th>@lang('Roles')</th>
                        <th>@lang('Actions')</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($admins as $admin)
                        <tr>
                            <td>{{ $admin->id }}</td>
                            <td>{{ $admin->name }}</td>
                            <td>{{ $admin->email }}</td>
                            <td>
                                @if($admin->roles->count() > 0)
                                    @foreach($admin->roles as $role)
                                        <div class="d-inline-flex align-items-center me-1">
                                            <span class="badge bg-label-info">{{ $role->name }}</span>
                                            @can('assign roles')
                                                <button wire:click="removeRole({{ $admin->id }}, {{ $role->id }})" 
                                                        class="btn btn-xs btn-outline-danger ms-1 p-0 px-1"
                                                        @if($admin->email === 'admin@admin.com' || ($admin->id === auth('admin')->id() && $admin->roles->count() <= 1)) disabled @endif
                                                        title="Remove role">
                                                    <i class="ti tabler-x"></i>
                                                </button>
                                            @endcan
                                        </div>
                                    @endforeach
                                @else
                                    <span class="text-muted">@lang('No roles assigned')</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex gap-2 justify-content-center">
                                    @can('assign roles')
                                        <button class="btn btn-sm btn-success" wire:click="openModal({{ $admin->id }})"
                                                @if($admin->email === 'admin@admin.com') disabled @endif>
                                            <i class="icon-base ti tabler-user-check"></i>
                                        </button>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">@lang('No admins found')</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <hr>
        <div class="card-footer">{{ $admins->links() }}</div>
    </div>

    <!-- Modal -->
    @if($is_open)
        <div class="modal fade show" style="display: block;" id="adminRolesModal" tabindex="-1" aria-labelledby="adminRolesModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="adminRolesModalLabel">
                            @lang('Assign Roles to'): {{ $admin_name }}
                        </h5>
                        <button type="button" class="btn-close" wire:click="closeModal" aria-label="Close"></button>
                    </div>
                    <form wire:submit.prevent="updateRoles">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">@lang('Select Roles')</label>
                                <div class="row">
                                    @foreach($roles as $role)
                                        <div class="col-md-12 mb-2">
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" id="role_{{ $role->id }}"
                                                       wire:model="selected_roles" value="{{ $role->id }}">
                                                <label class="form-check-label text-capitalize" for="role_{{ $role->id }}">
                                                    {{ $role->name }}
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" wire:click="closeModal">@lang('Close')</button>
                            <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                                <span wire:loading wire:target="updateRoles" class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                                @lang('Update Roles')
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
</div>
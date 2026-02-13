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
                        <th>Image</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Assigned Roles</th>
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
                            <td>
                                @forelse($admin->roles as $role)
                                    <span class="badge bg-label-primary me-1">{{ $role->name }}</span>
                                @empty
                                    <span class="text-muted">No roles</span>
                                @endforelse
                            </td>
                            <td>
                                @can('assign roles')
                                    <button class="btn btn-sm btn-success" wire:click="openModal({{ $admin->id }})"
                                            title="Manage Roles">
                                        <i class="icon-base ti tabler-user-cog"></i>
                                    </button>
                                @endcan
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

    <!-- Roles Modal -->
    @if($is_open)
        <div class="modal fade show" style="display: block;" id="rolesModal" tabindex="-1" aria-labelledby="rolesModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="rolesModalLabel">
                            {{ __('Assign Roles to') }}: {{ $admin_name }}
                        </h5>
                        <button type="button" class="btn-close" wire:click="closeModal" aria-label="Close"></button>
                    </div>
                    <form wire:submit.prevent="updateRoles">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Select Roles</label>
                                <div class="row">
                                    @foreach($roles as $role)
                                        <div class="col-md-6 mb-2">
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input"
                                                       id="role_{{ $role->id }}"
                                                       value="{{ $role->id }}"
                                                       wire:model="selected_roles">
                                                <label class="form-check-label" for="role_{{ $role->id }}">
                                                    {{ $role->name }}
                                                    @if(in_array($role->name, ['Super Admin']))
                                                        <span class="badge bg-label-danger">System</span>
                                                    @endif
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" wire:click="closeModal">Close</button>
                            <button type="submit" class="btn btn-success" wire:loading.attr="disabled">
                                <span wire:loading wire:target="updateRoles" class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                                {{ __('Update Roles') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show"></div>
    @endif
</div>

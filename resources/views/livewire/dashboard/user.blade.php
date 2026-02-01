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
                        <button class="btn btn-primary">Add New User</button>
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
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Wallet</th>
                        <th>Block</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td>{{$user->id}}</td>
                            <td>{{$user->name}}</td>
                            <td>{{$user->email}}</td>
                            <td>{{$user->phone}}</td>
                            <td>{{$user->wallet}}</td>
                            <td>
                                <label class="switch switch-primary">
                                    <input type="checkbox" class="switch-input"
                                           {{$user->is_block ? 'checked' : ''}} wire:click="block({{ $user->id }})"/>
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
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <hr>
        <div class="card-footer">{{$users->links()}}</div>
    </div>
</div>

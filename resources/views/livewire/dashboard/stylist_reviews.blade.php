<div>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">@lang('Stylist Reviews')</h3>
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
                        <button class="btn btn-primary" wire:click="create">Add New Review</button>
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
                        <th>User</th>
                        <th>Rating</th>
                        <th>Review</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($stylist_reviews as $stylistReview)
                        <tr>
                            <td>{{ $stylistReview->id }}</td>
                            <td>{{ $stylistReview->stylist->name ?? 'N/A' }}</td>
                            <td>{{ $stylistReview->user->name ?? 'N/A' }}</td>
                            <td>
                                <div class="text-warning">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $stylistReview->rating)
                                            <i class="icon-base ti tabler-star-filled"></i>
                                        @else
                                            <i class="icon-base ti tabler-star"></i>
                                        @endif
                                    @endfor
                                </div>
                            </td>
                            <td>{{ Str::limit($stylistReview->review, 50) }}</td>
                            <td>
                                <div class="d-flex gap-2 justify-content-center">
                                    <button class="btn btn-sm btn-info" wire:click="edit({{ $stylistReview->id }})">
                                        <i class="icon-base ti tabler-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger" wire:click="delete({{ $stylistReview->id }})"
                                            onclick="confirm('Are you sure you want to delete this review?') || event.stopImmediatePropagation()">
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
        <div class="card-footer">{{ $stylist_reviews->links() }}</div>
    </div>

    <!-- Modal -->
    @if($is_open)
        <div class="modal fade show" style="display: block;" id="stylistReviewModal" tabindex="-1" aria-labelledby="stylistReviewModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="stylistReviewModalLabel">
                            {{ $review_id ? __('Edit Review') : __('Add New Review') }}
                        </h5>
                        <button type="button" class="btn-close" wire:click="closeModal" aria-label="Close"></button>
                    </div>
                    <form wire:submit.prevent="{{ $review_id ? 'update' : 'store' }}">
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
                                <label for="user_id" class="form-label">User <span class="text-danger">*</span></label>
                                <select id="user_id" class="form-select" wire:model="user_id">
                                    <option value="">Select User</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                                @error('user_id')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="rating" class="form-label">Rating <span class="text-danger">*</span></label>
                                <select id="rating" class="form-select" wire:model="rating">
                                    <option value="5">5 - Excellent</option>
                                    <option value="4">4 - Very Good</option>
                                    <option value="3">3 - Good</option>
                                    <option value="2">2 - Fair</option>
                                    <option value="1">1 - Poor</option>
                                </select>
                                @error('rating')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="review" class="form-label">Review <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="review" wire:model="review" rows="4"
                                          placeholder="Enter your review"></textarea>
                                @error('review')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" wire:click="closeModal">Close</button>
                            <button type="submit" class="btn btn-primary">
                                {{ $review_id ? __('Update') : __('Save') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show"></div>
    @endif
</div>

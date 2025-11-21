{{-- resources/views/review/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="mb-0 h3 fw-bold text-dark">
            {{ $stageLabel }} – Review Queue
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="container-fluid">

            @if(session('success'))
                <div class="shadow-sm alert alert-success alert-dismissible fade show rounded-4">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if($vendors->count() === 0)
                <div class="border-0 shadow-sm card rounded-4">
                    <div class="py-8 text-center card-body">
                        <p class="mb-0 text-muted fs-4">
                            No vendors are currently waiting for {{ strtolower($stageLabel) }}.
                        </p>
                        <small>Great job staying on top of reviews!</small>
                    </div>
                </div>
            @endif

            <div class="row g-4">
                @foreach($vendors as $vendor)
                    <div class="col-12 col-lg-6 col-xl-4">
                        <div class="overflow-hidden border-0 shadow-lg card rounded-4 h-100">
                            <div class="py-3 text-white card-header bg-primary">
                                <h5 class="mb-0 fw-bold">{{ $vendor->vendor_name }}</h5>
                                <small>{{ $vendor->email }}</small>
                            </div>

                            <div class="card-body">
                                <div class="mb-3">
                                    <strong>Phone:</strong> {{ $vendor->phone ?? '—' }}<br>
                                    <strong>Address:</strong> {{ $vendor->address ?? '—' }}<br>
                                    <strong>Category:</strong> 
                                        <span class="badge bg-info text-dark">{{ $vendor->category }}</span><br>
                                    <strong>Created by:</strong> {{ $vendor->creator->name ?? 'Unknown' }}
                                </div>

                                <div class="px-3 py-2 mb-3 alert alert-info small">
                                    <strong>Who Acts Next:</strong> 
                                    {{ $workflow->getNextActionLabel($vendor) }}
                                </div>

                                <!-- APPROVE & REJECT BUTTONS – THIS WAS MISSING! -->
                                <div class="gap-2 d-grid d-md-flex justify-content-md-end">
                                    <!-- APPROVE BUTTON -->
                                    <form method="POST" action="{{ route('review.approve', $vendor) }}" class="d-inline">
                                        @csrf
                                        <button type="submit" class="px-4 btn btn-success btn-lg fw-bold">
                                            Approve & Move Forward
                                        </button>
                                    </form>

                                    <!-- REJECT BUTTON (with modal) -->
                                    <button type="button" class="px-4 btn btn-danger btn-lg fw-bold" data-bs-toggle="modal" data-bs-target="#rejectModal-{{ $vendor->id }}">
                                        Reject
                                    </button>
                                </div>
                            </div>

                            <!-- History Timeline -->
                            <div class="card-footer bg-light">
                                <details class="dropdown">
                                    <summary class="cursor-pointer text-primary fw-semibold">
                                        View History ({{ $vendor->histories->count() }})
                                    </summary>
                                    <div class="mt-3">
                                        @foreach($vendor->histories->sortByDesc('created_at') as $history)
                                            <div class="mb-2 small text-muted border-start border-3 border-primary ps-3">
                                                <strong>{{ $history->action }}</strong> @ {{ $history->stage }}<br>
                                                @if($history->comment)
                                                    <em>"{{ $history->comment }}"</em><br>
                                                @endif
                                                <span class="text-muted">
                                                    by {{ $history->performer->name ?? 'Unknown' }} 
                                                    • {{ $history->created_at->diffForHumans() }}
                                                </span>
                                            </div>
                                        @endforeach
                                    </div>
                                </details>
                            </div>
                        </div>
                    </div>

                    <!-- Reject Modal -->
                    <div class="modal fade" id="rejectModal-{{ $vendor->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <form method="POST" action="{{ route('review.reject', $vendor) }}">
                                @csrf
                                <div class="shadow-lg modal-content rounded-4">
                                    <div class="text-white modal-header bg-danger">
                                        <h5 class="modal-title">Reject Vendor</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p>Please provide a reason for rejection. This will be sent back to the vendor.</p>
                                        <textarea name="comment" class="form-control" rows="4" required 
                                                  placeholder="e.g. Missing tax certificate, unclear pricing structure..."></textarea>
                                        @error('comment')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-danger fw-bold">Reject & Send Back</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Back to Dashboard -->
            <div class="mt-5 text-center">
                <a href="{{ route('dashboard') }}" class="btn btn-outline-primary btn-lg">
                    Back to Dashboard
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
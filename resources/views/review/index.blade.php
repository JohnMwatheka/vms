{{-- resources/views/review/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="container-fluid">
            <h2 class="mb-0 h3 fw-bold text-dark">
                <i class="fas fa-tasks me-3 text-primary"></i>
                {{ $stageLabel }} – Review Queue
            </h2>
        </div>
    </x-slot>

    <div class="py-4">
        <div class="container-fluid">

            @if(session('success'))
                <div class="mb-4 border-0 shadow-sm alert alert-success alert-dismissible fade show" 
                     style="border-radius: 16px; background: linear-gradient(135deg, #d1f2eb 0%, #a8e6cf 100%);">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-check-circle text-success me-3 fs-5"></i>
                        <span class="fw-semibold text-success">{{ session('success') }}</span>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if($vendors->count() === 0)
                <div class="border-0 shadow-sm card" style="border-radius: 20px;">
                    <div class="py-5 text-center card-body">
                        <i class="mb-4 fas fa-inbox display-1 text-muted"></i>
                        <h4 class="mb-3 text-muted fw-bold">No Pending Reviews</h4>
                        <p class="mb-0 text-muted fs-5">
                            No vendors are currently waiting for {{ strtolower($stageLabel) }}.
                        </p>
                        <div class="mt-4">
                            <a href="{{ route('dashboard') }}" 
                               class="px-5 py-3 border-2 btn btn-outline-primary btn-lg fw-semibold"
                               style="border-radius: 12px;">
                                <i class="fas fa-arrow-left me-2"></i>
                                Back to Dashboard
                            </a>
                        </div>
                    </div>
                </div>
            @else
                <!-- Vendor Table Section -->
                <div class="border-0 shadow-lg card" style="border-radius: 20px;">
                    <div class="px-4 py-3 text-white card-header" 
                         style="background: linear-gradient(135deg, #0d6efd 0%, #6610f2 100%); border: none; border-radius: 20px 20px 0 0;">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <h5 class="mb-0 fw-bold">
                                    <i class="fas fa-list me-2"></i>
                                    Vendors Pending Review ({{ $vendors->count() }})
                                </h5>
                            </div>
                            <div class="col-md-6 text-md-end">
                                <span class="bg-white badge text-primary fs-7">
                                    {{ $stageLabel }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="p-0 card-body">
                        <div class="table-responsive">
                            <table class="table mb-0 table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th class="py-3 ps-4 fw-bold text-dark">Vendor Details</th>
                                        <th class="py-3 fw-bold text-dark">Contact Info</th>
                                        <th class="py-3 fw-bold text-dark">Status</th>
                                        <th class="py-3 fw-bold text-dark">History</th>
                                        <th class="py-3 text-center pe-4 fw-bold text-dark">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($vendors as $vendor)
                                        <tr class="border-bottom">
                                            <!-- Vendor Details Column -->
                                            <td class="py-3 ps-4">
                                                <div class="d-flex flex-column">
                                                    <h6 class="mb-1 fw-bold text-dark">{{ $vendor->vendor_name }}</h6>
                                                    <div class="mb-1 d-flex align-items-center">
                                                        <span class="badge bg-primary fs-8 me-2">{{ $vendor->category }}</span>
                                                        <small class="text-muted fs-8">By: {{ $vendor->creator->name ?? 'Unknown' }}</small>
                                                    </div>
                                                    <small class="text-muted fs-8">
                                                        <i class="fas fa-calendar me-1"></i>
                                                        {{ $vendor->created_at->diffForHumans() }}
                                                    </small>
                                                </div>
                                            </td>

                                            <!-- Contact Info Column -->
                                            <td class="py-3">
                                                <div class="d-flex flex-column">
                                                    <div class="mb-1 d-flex align-items-center">
                                                        <i class="fas fa-envelope text-muted me-2 fs-8"></i>
                                                        <small class="text-dark fs-8">{{ $vendor->email }}</small>
                                                    </div>
                                                    <div class="mb-1 d-flex align-items-center">
                                                        <i class="fas fa-phone text-muted me-2 fs-8"></i>
                                                        <small class="text-dark fs-8">{{ $vendor->phone ?? '—' }}</small>
                                                    </div>
                                                    <div class="d-flex align-items-start">
                                                        <i class="mt-1 fas fa-map-marker-alt text-muted me-2 fs-8"></i>
                                                        <small class="text-dark fs-8">{{ Str::limit($vendor->address, 40) ?? '—' }}</small>
                                                    </div>
                                                </div>
                                            </td>

                                            <!-- Status Column -->
                                            <td class="py-3">
                                                <div class="d-flex flex-column">
                                                    <span class="mb-1 badge bg-info fs-8">Current Stage</span>
                                                    <small class="fw-semibold text-dark fs-8">{{ $vendor->current_stage->label() }}</small>
                                                    <div class="mt-1">
                                                        <span class="border badge bg-light text-dark fs-8">Next: {{ $workflow->getNextActionLabel($vendor) }}</span>
                                                    </div>
                                                </div>
                                            </td>

                                            <!-- History Column -->
                                            <td class="py-3">
                                                <div class="d-flex flex-column">
                                                    @if($vendor->histories->count() > 0)
                                                        @php
                                                            $latestHistory = $vendor->histories->sortByDesc('created_at')->first();
                                                        @endphp
                                                        <button type="button" 
                                                                class="p-0 border-0 btn btn-link text-decoration-none text-start"
                                                                data-bs-toggle="modal" 
                                                                data-bs-target="#historyModal-{{ $vendor->id }}">
                                                            <span class="mb-1 badge bg-secondary fs-8">
                                                                <i class="fas fa-history me-1"></i>
                                                                {{ $vendor->histories->count() }} entries
                                                            </span>
                                                        </button>
                                                        <small class="text-muted fs-8">
                                                            <strong>{{ $latestHistory->action }}</strong>
                                                        </small>
                                                        <small class="text-muted fs-8">
                                                            by {{ $latestHistory->performer->name ?? 'Unknown' }}
                                                        </small>
                                                        <small class="text-muted fs-8">
                                                            {{ $latestHistory->created_at->diffForHumans() }}
                                                        </small>
                                                    @else
                                                        <span class="border badge bg-light text-muted fs-8">No history</span>
                                                    @endif
                                                </div>
                                            </td>

                                            <!-- Actions Column -->
                                            <td class="py-3 pe-4">
                                                <div class="gap-1 d-flex flex-column">
                                                    <!-- Approve Button -->
                                                    <form method="POST" action="{{ route('review.approve', $vendor) }}" class="w-100">
                                                        @csrf
                                                        <button type="submit" 
                                                                class="py-2 transition-all border-0 shadow btn btn-success w-100 fw-bold"
                                                                style="border-radius: 6px; background: linear-gradient(135deg, #198754 0%, #20c997 100%); font-size: 0.8rem;"
                                                                onmouseover="this.style.transform='translateY(-1px)';"
                                                                onmouseout="this.style.transform='translateY(0)';">
                                                            <i class="fas fa-check me-1"></i>
                                                            Approve
                                                        </button>
                                                    </form>

                                                    <!-- Reject Button -->
                                                    <button type="button" 
                                                            class="py-2 border-0 shadow w-100 btn btn-danger fw-bold"
                                                            style="border-radius: 6px; background: linear-gradient(135deg, #dc3545 0%, #c82333 100%); font-size: 0.8rem;"
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#rejectModal-{{ $vendor->id }}"
                                                            onmouseover="this.style.transform='translateY(-1px)';"
                                                            onmouseout="this.style.transform='translateY(0)';">
                                                        <i class="fas fa-times me-1"></i>
                                                        Reject
                                                    </button>

                                                    <!-- View Details Button -->
                                                    <button type="button" 
                                                            class="py-2 border-0 w-100 btn btn-outline-primary fw-semibold"
                                                            style="border-radius: 6px; font-size: 0.8rem;"
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#detailsModal-{{ $vendor->id }}">
                                                        <i class="fas fa-eye me-1"></i>
                                                        Details
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>

                                        <!-- History Modal -->
                                        <div class="modal fade" id="historyModal-{{ $vendor->id }}" tabindex="-1" aria-labelledby="historyModalLabel-{{ $vendor->id }}" aria-hidden="true">
                                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                                <div class="border-0 shadow-lg modal-content" style="border-radius: 20px;">
                                                    <div class="py-4 text-white modal-header" 
                                                         style="background: linear-gradient(135deg, #6c757d 0%, #495057 100%); border-radius: 20px 20px 0 0; border: none;">
                                                        <h5 class="modal-title fw-bold" id="historyModalLabel-{{ $vendor->id }}">
                                                            <i class="fas fa-history me-2"></i>
                                                            Review History: {{ $vendor->vendor_name }}
                                                        </h5>
                                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="py-4 modal-body">
                                                        <div class="mb-3">
                                                            <span class="badge bg-primary fs-7">{{ $vendor->histories->count() }} total entries</span>
                                                        </div>
                                                        <div class="timeline">
                                                            @foreach($vendor->histories->sortByDesc('created_at') as $index => $history)
                                                                <div class="mb-4 timeline-item ps-4 position-relative">
                                                                    <div class="top-0 position-absolute start-0 bg-primary rounded-circle" 
                                                                         style="width: 12px; height: 12px; margin-top: 6px;"></div>
                                                                    <div class="border-start border-3 border-primary ps-3">
                                                                        <div class="mb-1 d-flex justify-content-between align-items-start">
                                                                            <strong class="text-dark fs-6">{{ $history->action }}</strong>
                                                                            <span class="badge bg-secondary fs-8">{{ $history->stage }}</span>
                                                                        </div>
                                                                        @if($history->comment)
                                                                            <div class="p-2 mt-1 mb-2 rounded bg-light">
                                                                                <em class="text-muted fs-7">"{{ $history->comment }}"</em>
                                                                            </div>
                                                                        @endif
                                                                        <div class="d-flex justify-content-between align-items-center">
                                                                            <small class="text-muted fs-8">
                                                                                <i class="fas fa-user me-1"></i>
                                                                                {{ $history->performer->name ?? 'Unknown' }}
                                                                            </small>
                                                                            <small class="text-muted fs-8">
                                                                                <i class="fas fa-clock me-1"></i>
                                                                                {{ $history->created_at->format('M j, Y g:i A') }}
                                                                            </small>
                                                                        </div>
                                                                        <small class="text-muted fs-8">
                                                                            {{ $history->created_at->diffForHumans() }}
                                                                        </small>
                                                                    </div>
                                                                </div>
                                                                @if(!$loop->last)
                                                                    <div class="mb-2"></div>
                                                                @endif
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                    <div class="py-4 border-0 modal-footer">
                                                        <button type="button" 
                                                                class="px-4 py-2 border-0 btn btn-outline-secondary fw-semibold"
                                                                style="border-radius: 8px; background: #f8f9fa;"
                                                                data-bs-dismiss="modal">
                                                            Close History
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Reject Modal -->
                                        <div class="modal fade" id="rejectModal-{{ $vendor->id }}" tabindex="-1" aria-labelledby="rejectModalLabel-{{ $vendor->id }}" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="border-0 shadow-lg modal-content" style="border-radius: 20px;">
                                                    <div class="py-4 text-white modal-header" 
                                                         style="background: linear-gradient(135deg, #dc3545 0%, #c82333 100%); border-radius: 20px 20px 0 0; border: none;">
                                                        <h5 class="modal-title fw-bold" id="rejectModalLabel-{{ $vendor->id }}">
                                                            <i class="fas fa-exclamation-triangle me-2"></i>
                                                            Reject Vendor
                                                        </h5>
                                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <form method="POST" action="{{ route('review.reject', $vendor) }}">
                                                        @csrf
                                                        <div class="py-4 modal-body">
                                                            <p class="mb-3 text-dark">
                                                                Please provide a reason for rejecting <strong>{{ $vendor->vendor_name }}</strong>.
                                                            </p>
                                                            <div class="form-group">
                                                                <label for="comment-{{ $vendor->id }}" class="form-label fw-semibold text-dark">Reason for Rejection</label>
                                                                <textarea name="comment" 
                                                                          class="border-0 shadow-sm form-control" 
                                                                          style="border-radius: 12px; padding: 12px 16px; background: #f8f9fa;"
                                                                          rows="4" 
                                                                          required 
                                                                          placeholder="e.g. Missing tax certificate, unclear pricing structure..."></textarea>
                                                                @error('comment')
                                                                    <small class="mt-2 text-danger">{{ $message }}</small>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="py-4 border-0 modal-footer">
                                                            <button type="button" 
                                                                    class="px-4 py-2 border-0 btn btn-outline-secondary fw-semibold"
                                                                    style="border-radius: 12px; background: #f8f9fa;"
                                                                    data-bs-dismiss="modal">
                                                                Cancel
                                                            </button>
                                                            <button type="submit" 
                                                                    class="px-4 py-2 border-0 shadow btn btn-danger fw-bold"
                                                                    style="border-radius: 12px; background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);">
                                                                <i class="fas fa-paper-plane me-2"></i>
                                                                Reject Vendor
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Details Modal -->
                                        <div class="modal fade" id="detailsModal-{{ $vendor->id }}" tabindex="-1" aria-labelledby="detailsModalLabel-{{ $vendor->id }}" aria-hidden="true">
                                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                                <div class="border-0 shadow-lg modal-content" style="border-radius: 20px;">
                                                    <div class="py-4 text-white modal-header" 
                                                         style="background: linear-gradient(135deg, #0d6efd 0%, #6610f2 100%); border-radius: 20px 20px 0 0; border: none;">
                                                        <h5 class="modal-title fw-bold" id="detailsModalLabel-{{ $vendor->id }}">
                                                            <i class="fas fa-info-circle me-2"></i>
                                                            Vendor Details: {{ $vendor->vendor_name }}
                                                        </h5>
                                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="py-4 modal-body">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <h6 class="mb-3 fw-bold text-dark">Basic Information</h6>
                                                                <div class="mb-2">
                                                                    <strong class="text-muted">Email:</strong>
                                                                    <span class="ms-2 text-dark">{{ $vendor->email }}</span>
                                                                </div>
                                                                <div class="mb-2">
                                                                    <strong class="text-muted">Phone:</strong>
                                                                    <span class="ms-2 text-dark">{{ $vendor->phone ?? '—' }}</span>
                                                                </div>
                                                                <div class="mb-2">
                                                                    <strong class="text-muted">Category:</strong>
                                                                    <span class="ms-2 badge bg-primary fs-8">{{ $vendor->category }}</span>
                                                                </div>
                                                                <div class="mb-2">
                                                                    <strong class="text-muted">Created by:</strong>
                                                                    <span class="ms-2 text-dark">{{ $vendor->creator->name ?? 'Unknown' }}</span>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <h6 class="mb-3 fw-bold text-dark">Address</h6>
                                                                <p class="text-dark">{{ $vendor->address ?? '—' }}</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="py-4 border-0 modal-footer">
                                                        <button type="button" 
                                                                class="px-4 py-2 border-0 btn btn-outline-secondary fw-semibold"
                                                                style="border-radius: 12px; background: #f8f9fa;"
                                                                data-bs-dismiss="modal">
                                                            Close
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Card Footer -->
                    <div class="py-3 border-0 card-footer bg-light">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <small class="text-muted fs-8">
                                    Showing {{ $vendors->count() }} vendor(s) in {{ $stageLabel }}
                                </small>
                            </div>
                            <div class="col-md-6 text-md-end">
                                <a href="{{ route('dashboard') }}" 
                                   class="px-4 py-2 border-2 btn btn-outline-primary fw-semibold fs-8"
                                   style="border-radius: 8px;">
                                    <i class="fas fa-arrow-left me-2"></i>
                                    Back to Dashboard
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        .table th {
            border-top: none;
            font-weight: 600;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .table td {
            vertical-align: middle;
            border-bottom: 1px solid #dee2e6;
        }
        
        .table tbody tr:hover {
            background-color: #f8f9fa;
        }
        
        .table-responsive {
            border-radius: 0 0 20px 20px;
        }
        
        .badge {
            font-size: 0.7rem;
            padding: 0.25rem 0.5rem;
        }
        
        .fs-7 {
            font-size: 0.8rem !important;
        }
        
        .fs-8 {
            font-size: 0.75rem !important;
        }
        
        .btn {
            transition: all 0.3s ease;
            font-size: 0.8rem;
        }
        
        .timeline-item:last-child {
            margin-bottom: 0 !important;
        }
        
        @media (max-width: 768px) {
            .table-responsive {
                font-size: 0.8rem;
            }
            
            .table th,
            .table td {
                padding: 0.5rem 0.25rem;
            }
            
            .btn {
                padding: 0.4rem 0.6rem;
                font-size: 0.75rem;
            }
            
            .badge {
                font-size: 0.65rem;
                padding: 0.2rem 0.4rem;
            }
        }
        
        @media (max-width: 576px) {
            .table-responsive {
                border: none;
            }
            
            .card-body {
                padding: 0 !important;
            }
        }
    </style>
</x-app-layout>
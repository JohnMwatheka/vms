<x-app-layout>
    <x-slot name="header">
        <div class="bg-transparent container-fluid">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h3 class="mb-0 h3 fw-bold text-dark">
                        <i class="fas fa-tachometer-alt me-3 text-primary"></i>
                        Vendor Management System 
                    </h3>
                </div>
                <div class="col-md-4 text-md-end">
                    <div class="border-0 shadow-sm card d-inline-block">
                        <div class="p-3 card-body">
                            <div class="d-flex align-items-center justify-content-end">
                                <i class="fas fa-user-circle me-3 text-primary fs-5"></i>
                                <div class="text-end">
                                    <p class="mb-0 fw-bold text-dark small">
                                        {{ auth()->user()->name }}
                                        <span class="badge bg-primary ms-2 fs-7">
                                            {{ auth()->user()->getRoleNames()->implode(', ') ?: 'No role' }}
                                        </span>
                                    </p>
                                    @if(session('demo_role'))
                                        <small class="text-muted">
                                            <i class="fas fa-user-shield me-1"></i>
                                            Demo: {{ ucfirst(session('demo_role')) }}
                                        </small>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-4">
        <div class="container-fluid">
            <div class="row g-4">

                <!-- DEMO ROLE SWITCHER -->
                <div class="col-12">
                    <x-role-switcher />
                </div>

                @if(session('success'))
                    <div class="col-12">
                        <div class="border-0 shadow-sm alert alert-success alert-dismissible fade show" 
                             style="border-radius: 16px; background: linear-gradient(135deg, #d1f2eb 0%, #a8e6cf 100%);">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-check-circle text-success me-3 fs-5"></i>
                                <span class="fw-semibold text-success">{{ session('success') }}</span>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    </div>
                @endif

                @if(session('demo_role'))
                    <div class="text-center col-12">
                        <span class="px-4 py-3 border-0 badge bg-primary fs-6" 
                              style="border-radius: 50px; background: linear-gradient(135deg, #0d6efd 0%, #6610f2 100%);">
                            <i class="fas fa-user-shield me-2"></i>
                            Demo Mode: {{ ucfirst(session('demo_role')) }}
                        </span>
                    </div>
                @endif

                {{-- 1. INITIATOR SECTION --}}
               
                @if(auth()->user()->hasRole('initiator') || session('demo_role') === 'initiator')
                    <div class="my-4 text-center col-12">
                        <a href="{{ route('vendor.create') }}" 
                           class="px-6 py-4 transition-all border-0 shadow btn btn-primary btn-lg fw-bold"
                           style="border-radius: 16px; background: linear-gradient(135deg, #0d6efd 0%, #6610f2 100%); font-size: 1.25rem;"
                           onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 8px 25px rgba(13, 110, 253, 0.4)';"
                           onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(13, 110, 253, 0.3)';">
                            <i class="fas fa-plus-circle me-3"></i>
                            Create New Vendor
                        </a>
                    </div>

                    @php
                        $pending = \App\Models\Vendor::where('current_stage', 'new')
                            ->where('created_by', auth()->id())
                            ->latest()
                            ->get();
                    @endphp

                    @if($pending->count() > 0)
                        <div class="col-12">
                            <div class="border-0 shadow-lg card" style="border-radius: 20px;">
                                <div class="py-4 card-header text-dark" 
                                     style="background: linear-gradient(135deg, #ffc107 0%, #ffb300 100%); border: none; border-radius: 20px 20px 0 0;">
                                    <h4 class="mb-0 fw-bold">
                                        <i class="fas fa-clock me-3"></i>
                                        Pending: Send to Vendor
                                    </h4>
                                </div>
                                <div class="p-4 card-body">
                                    <div class="row g-3">
                                        @foreach($pending as $vendor)
                                            <div class="col-12">
                                                <div class="border-0 shadow-sm card bg-light">
                                                    <div class="p-4 card-body">
                                                        <div class="row align-items-center">
                                                            <div class="col-12 col-md-8">
                                                                <h5 class="mb-2 fw-bold text-dark">{{ $vendor->vendor_name }}</h5>
                                                                <p class="mb-0 text-muted">
                                                                    <i class="fas fa-envelope me-2"></i>{{ $vendor->email }}
                                                                </p>
                                                                <small class="text-muted">
                                                                    <i class="fas fa-calendar me-2"></i>
                                                                    Created {{ $vendor->created_at->diffForHumans() }}
                                                                </small>
                                                            </div>
                                                            <div class="mt-3 col-12 col-md-4 text-md-end mt-md-0">
                                                                <form method="POST" action="{{ route('vendor.send', $vendor) }}" class="d-inline">
                                                                    @csrf
                                                                    <button type="submit" 
                                                                            class="px-5 py-3 transition-all border-0 shadow btn btn-success btn-lg fw-bold"
                                                                            style="border-radius: 12px; background: linear-gradient(135deg, #198754 0%, #20c997 100%);"
                                                                            onmouseover="this.style.transform='translateY(-2px)';"
                                                                            onmouseout="this.style.transform='translateY(0)';">
                                                                        <i class="fas fa-paper-plane me-2"></i>
                                                                        Send to Vendor
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @endif
                {{-- 2. VENDOR PORTAL SECTION --}}
                
                @php
                    $vendorRecord = \App\Models\Vendor::where('email', auth()->user()->email)
                        ->where('current_stage', 'with_vendor')
                        ->first();

                    $underReviewRecord = \App\Models\Vendor::where('email', auth()->user()->email)
                        ->where('current_stage', '!=', 'with_vendor')
                        ->where('current_stage', '!=', 'new')
                        ->first();
                @endphp

                @if($vendorRecord || $underReviewRecord)
                    <div class="col-12">
                        @if($vendorRecord)
                            <!-- Vendor needs to fill details -->
                            <div class="overflow-hidden text-white border-0 shadow-lg card" 
                                 style="border-radius: 20px; background: linear-gradient(135deg, #0d6efd 0%, #6610f2 100%);">
                                <div class="p-5 card-body">
                                    <div class="row align-items-center">
                                        <div class="col-12 col-md-8">
                                            <h2 class="mb-3 fw-bold">
                                                <i class="fas fa-hand-wave me-3"></i>
                                                Welcome, {{ $vendorRecord->vendor_name ?? 'Vendor' }}!
                                            </h2>
                                            <h4 class="mb-3">Action Required</h4>
                                            <p class="mb-4 fs-5 opacity-90">
                                                Please complete your company profile to begin the approval process.
                                            </p>
                                            <a href="{{ route('vendor.portal') }}" 
                                               class="px-5 py-3 transition-all border-0 shadow btn btn-light btn-lg fw-bold text-primary"
                                               style="border-radius: 12px;"
                                               onmouseover="this.style.transform='translateY(-2px)';"
                                               onmouseout="this.style.transform='translateY(0)';">
                                                <i class="fas fa-edit me-2"></i>
                                                Complete Profile Now
                                            </a>
                                        </div>
                                        <div class="text-center col-12 col-md-4 d-none d-md-block">
                                            <i class="opacity-25 fas fa-clipboard-list display-1"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @elseif($underReviewRecord)
                            <!-- Already submitted – under review -->
                            <div class="border-0 shadow-lg card" style="border-radius: 20px;">
                                <div class="p-5 card-body">
                                    <div class="row align-items-center">
                                        <div class="col-12 col-md-8">
                                            <div class="mb-4 d-flex align-items-center">
                                                <i class="fas fa-check-circle text-success me-3 fs-1"></i>
                                                <div>
                                                    <h4 class="mb-1 text-success fw-bold">
                                                        Thank You! Your Profile Has Been Submitted
                                                    </h4>
                                                    <p class="mb-0 text-muted">
                                                        Current Stage: 
                                                        <strong class="text-primary">{{ $underReviewRecord->current_stage->label() }}</strong>
                                                    </p>
                                                </div>
                                            </div>
                                            <p class="mb-3 text-muted fs-5">
                                                <i class="fas fa-arrow-right me-2"></i>
                                                Who Acts Next: {{ $underReviewRecord->current_stage->nextActionLabel() }}
                                            </p>
                                        </div>
                                        <div class="text-center col-12 col-md-4 d-none d-md-block">
                                            <i class="opacity-25 text-muted fas fa-hourglass-half display-1"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                @endif
                {{-- 3. REVIEWERS SECTION --}}
               
                @if(
                    auth()->user()->hasAnyRole(['checker', 'procurement', 'legal', 'finance', 'directors']) ||
                    in_array(session('demo_role'), ['checker', 'procurement', 'legal', 'finance', 'directors'])
                )
                    <div class="col-12">
                        <div class="mb-4 d-flex justify-content-between align-items-center">
                            <h3 class="mb-0 h4 fw-bold text-dark">
                                <i class="fas fa-list-check me-3 text-primary"></i>
                                Review Stages
                            </h3>
                            @php
                                $totalPending = \App\Models\Vendor::whereIn('current_stage', ['checker_review', 'procurement_review', 'legal_review', 'finance_review', 'directors_review'])->count();
                            @endphp
                            @if($totalPending === 0)
                                <a href="{{ url('/dashboard') }}" 
                                   class="px-4 py-2 transition-all border-0 shadow btn btn-success btn-sm fw-bold"
                                   style="border-radius: 8px; background: linear-gradient(135deg, #198754 0%, #20c997 100%);"
                                   onmouseover="this.style.transform='translateY(-1px)';"
                                   onmouseout="this.style.transform='translateY(0)';">
                                    <i class="fas fa-check-circle me-2"></i>
                                    All Caught Up - Return to Dashboard
                                </a>
                            @else
                                <span class="px-3 py-2 text-white border-0 badge bg-danger fs-6 fw-bold" 
                                      style="border-radius: 8px;">
                                    <i class="fas fa-tasks me-2"></i>
                                    {{ $totalPending }} Total Pending Reviews
                                </span>
                            @endif
                        </div>
                        <div class="row g-3">
                            @php
                                $stages = [
                                    'checker_review'     => ['Checker Review',     'fas fa-search',      'linear-gradient(135deg, #0d6efd 0%, #6610f2 100%)'],
                                    'procurement_review' => ['Procurement Review', 'fas fa-shopping-cart', 'linear-gradient(135deg, #0dcaf0 0%, #3d8bfd 100%)'],
                                    'legal_review'       => ['Legal Review',       'fas fa-gavel',        'linear-gradient(135deg, #dc3545 0%, #fd7e14 100%)'],
                                    'finance_review'     => ['Finance Review',     'fas fa-chart-line',   'linear-gradient(135deg, #198754 0%, #20c997 100%)'],
                                    'directors_review'   => ['Directors Review',   'fas fa-user-tie',     'linear-gradient(135deg, #ffc107 0%, #fd7e14 100%)'],
                                ];
                            @endphp

                            @foreach($stages as $stage => $data)
                                @php
                                    $count = \App\Models\Vendor::where('current_stage', $stage)->count();
                                @endphp

                                <div class="col-12 col-md-6 col-lg-4">
                                    <a href="{{ $count > 0 ? route('review.stage', $stage) : '#' }}"
                                       class="transition-all border-0 shadow card h-100 text-decoration-none text-white {{ $count === 0 ? 'pe-none' : '' }}"
                                       style="border-radius: 12px; background: {{ $data[2] }}; {{ $count === 0 ? 'opacity: 0.7;' : '' }}"
                                       onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 8px 25px rgba(0,0,0,0.2)';"
                                       onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 8px rgba(0,0,0,0.1)';">
                                        <div class="p-3 text-center card-body">
                                            <i class="{{ $data[1] }} fs-4 mb-2" style="opacity: 0.9;"></i>
                                            <h6 class="mb-2 card-title fw-bold">{{ $data[0] }}</h6>
                                            <p class="mb-2 card-text" style="opacity: 0.9;">Click to review pending vendors</p>
                                            @if($count > 0)
                                                <span class="px-2 py-1 bg-white border-0 text-dark badge fs-7 fw-bold" 
                                                      style="border-radius: 8px; opacity: 0.95;">
                                                    {{ $count }} pending
                                                </span>
                                            @else
                                                <span class="px-2 py-1 border-0 text-dark bg-light badge fs-7 fw-bold" 
                                                      style="border-radius: 8px; opacity: 0.95;">
                                                    <i class="fas fa-check me-1"></i>All done
                                                </span>
                                            @endif
                                        </div>
                                    </a>
                                </div>
                            @endforeach

                            <!-- Approved Vendors Card -->
                            <div class="col-12 col-md-6 col-lg-4">
                                <a href="{{ route('approved.vendors') }}"
                                   class="text-white transition-all border-0 shadow card h-100 text-decoration-none"
                                   style="border-radius: 12px; background: linear-gradient(135deg, #0dcaf0 0%, #198754 100%);"
                                   onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 8px 25px rgba(0,0,0,0.2)';"
                                   onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 8px rgba(0,0,0,0.1)';">
                                    <div class="p-3 text-center card-body">
                                        <i class="mb-2 fas fa-check-double fs-4" style="opacity: 0.9;"></i>
                                        <h6 class="mb-2 card-title fw-bold">Approved Vendors</h6>
                                        <p class="mb-2 card-text" style="opacity: 0.9;">Master list of approved vendors</p>
                                        <span class="px-2 py-1 bg-white border-0 text-dark badge fs-7 fw-bold" 
                                              style="border-radius: 8px; opacity: 0.95;">
                                            View all
                                        </span>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>

    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        .card {
            transition: all 0.3s ease;
        }
        
        .btn {
            transition: all 0.3s ease;
        }
        
        .alert {
            border-radius: 16px;
        }
        
        .pe-none {
            pointer-events: none;
        }
        
        @media (max-width: 768px) {
            .card-body {
                padding: 1.5rem !important;
            }
            
            .btn-lg {
                padding: 1rem 2rem !important;
                font-size: 1.1rem !important;
            }
            
            .display-6 {
                font-size: 2.5rem !important;
            }
            
            .header-user-card {
                margin-top: 1rem;
            }
            
            .d-flex.justify-content-between {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
            }
        }
        
        @media (max-width: 576px) {
            .container-fluid {
                padding-left: 12px;
                padding-right: 12px;
            }
            
            .card-body {
                padding: 1rem !important;
            }
            
            .row.g-4 {
                margin-left: -0.5rem;
                margin-right: -0.5rem;
            }
            
            .row.g-4 > [class*="col-"] {
                padding-left: 0.5rem;
                padding-right: 0.5rem;
            }
            
            .header-user-card .card-body {
                padding: 0.75rem !important;
            }
        }
    </style>
</x-app-layout>
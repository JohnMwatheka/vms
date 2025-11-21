<x-app-layout>
    <x-slot name="header">
        <h2 class="h3 fw-bold text-dark mb-0">Vendor Management System – Dashboard</h2>
    </x-slot>

    <div class="py-6">
        <div class="container-fluid">
            <div class="row g-5">

                <!-- DEMO ROLE SWITCHER (Your killer feature) -->
                <div class="col-12 text-center mb-4">
                    <x-role-switcher class="d-inline-block" />
                </div>

                <!-- SUCCESS MESSAGE -->
                @if(session('success'))
                    <div class="col-12">
                        <div class="alert alert-success alert-dismissible fade show shadow-sm rounded-4 border-0">
                            <strong>{{ session('success') }}</strong>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    </div>
                @endif

                <!-- CURRENT DEMO ROLE (for clarity) -->
                @if(session('demo_role'))
                    <div class="col-12 text-center">
                        <span class="badge bg-primary fs-5 px-4 py-2">
                            Demo Mode: {{ ucfirst(session('demo_role')) }}
                        </span>
                    </div>
                @endif

                <!-- ================================================================= -->
                <!-- 1. INITIATOR DASHBOARD                                            -->
                <!-- ================================================================= -->
                @if(auth()->user()->hasRole('initiator') || session('demo_role') === 'initiator')
                    <div class="col-12 text-center my-5">
                        <a href="{{ route('vendor.create') }}"
                           class="btn btn-primary btn-lg px-6 py-3 shadow-lg fw-bold">
                            <i class="fas fa-plus-circle me-2"></i> Create New Vendor
                        </a>
                    </div>

                    @php
                        $pendingVendors = \App\Models\Vendor::where('current_stage', 'new')
                                            ->where('created_by', auth()->id())
                                            ->latest()
                                            ->get();
                    @endphp

                    @if($pendingVendors->count() > 0)
                        <div class="col-12">
                            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                                <div class="card-header bg-warning text-dark py-4">
                                    <h4 class="mb-0 fw-bold">Pending: Send to Vendor</h4>
                                    <p class="mb-0">These vendors are waiting for you to start the onboarding process.</p>
                                </div>
                                <div class="card-body p-4">
                                    @foreach($pendingVendors as $vendor)
                                        <div class="d-flex justify-content-between align-items-center p-4 bg-light rounded-3 mb-3 shadow-sm">
                                            <div>
                                                <h5 class="mb-1">{{ $vendor->vendor_name }}</h5>
                                                <p class="text-muted mb-0">{{ $vendor->email }} • Created {{ $vendor->created_at->diffForHumans() }}</p>
                                            </div>
                                            <form method="POST" action="{{ route('vendor.send', $vendor) }}" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-success btn-lg fw-bold px-5">
                                                    Send to Vendor
                                                </button>
                                            </form>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                @endif

                <!-- ================================================================= -->
                <!-- 2. VENDOR DASHBOARD                                               -->
                <!-- ================================================================= -->
                @if(auth()->user()->hasRole('vendor') || session('demo_role') === 'vendor')
                    @php
                        $vendorRecord = \App\Models\Vendor::where('email', auth()->user()->email)->first();
                    @endphp

                    <div class="col-12">
                        @if(!$vendorRecord)
                            <div class="card border-0 shadow-sm rounded-4 text-center p-8">
                                <p class="text-muted fs-4">No vendor profile found for your email.</p>
                            </div>

                        @elseif($vendorRecord->current_stage === 'new')
                            <div class="card bg-gradient-warning text-white rounded-4 shadow-lg">
                                <div class="card-body text-center py-8">
                                    <h3>Awaiting Initiator Action</h3>
                                    <p class="fs-5">Your profile has been created and is waiting to be sent to you.</p>
                                    <span class="badge bg-white text-warning fs-5 px-5 py-3">Status: New</span>
                                </div>
                            </div>

                        @elseif($vendorRecord->current_stage === 'with_vendor')
                            <div class="card bg-gradient-orange text-white rounded-4 shadow-lg">
                                <div class="card-body text-center py-8">
                                    <h3 class="fw-bold">Action Required</h3>
                                    <p class="fs-4 mb-5">Please complete your company profile to begin the approval process.</p>
                                    <a href="{{ route('vendor.portal') }}"
                                       class="btn btn-light btn-lg px-8 py-4 fw-bold shadow">
                                        Complete Profile Now
                                    </a>
                                </div>
                            </div>

                        @else
                            <div class="card bg-success text-white rounded-4 shadow-lg">
                                <div class="card-body text-center py-7">
                                    <h4>Your profile is under review</h4>
                                    <p class="fs-5 mb-0">
                                        Current Stage: <strong>{{ ucwords(str_replace('_', ' ', $vendorRecord->current_stage)) }}</strong>
                                    </p>
                                </div>
                            </div>
                        @endif
                    </div>
                @endif

                <!-- ================================================================= -->
                <!-- 3. REVIEWERS (Checker, Procurement, Legal, Finance, Directors)   -->
                <!-- ================================================================= -->
                @if(auth()->user()->hasAnyRole(['checker','procurement','legal','finance','directors']) ||
                    in_array(session('demo_role'), ['checker','procurement','legal','finance','directors']))
                    <div class="col-12">
                        <h3 class="h4 fw-bold mb-4">Review Stages</h3>
                        <div class="row g-4">

                            @php
                                $stages = [
                                    'checker_review'     => ['Checker Review', 'bg-primary'],
                                    'procurement_review' => ['Procurement Review', 'bg-purple'],
                                    'legal_review'       => ['Legal Review', 'bg-danger'],
                                    'finance_review'     => ['Finance Review', 'bg-success'],
                                    'directors_review'   => ['Directors Review', 'bg-warning text-dark'],
                                ];
                            @endphp

                            @foreach($stages as $stage => $data)
                                <div class="col-12 col-md-6 col-lg-4">
                                    <a href="{{ route('review.stage', $stage) }}"
                                       class="card text-white border-0 shadow h-100 text-decoration-none rounded-4 overflow-hidden
                                              bg-gradient {{ $data[1] === 'bg-purple' ? 'bg-gradient-purple' : $data[1] }}">
                                        <div class="card-body text-center py-5">
                                            <h5 class="fw-bold fs-4">{{ $data[0] }}</h5>
                                            <p class="opacity-90">Click to review pending vendors</p>
                                            @php
                                                $count = \App\Models\Vendor::where('current_stage', $stage)->count();
                                            @endphp
                                            @if($count > 0)
                                                <span class="badge bg-white text-dark fs-6 px-4 py-2">{{ $count }} pending</span>
                                            @endif
                                        </div>
                                    </a>
                                </div>
                            @endforeach

                            <div class="col-12 col-md-6 col-lg-4">
                                <a href="{{ route('approved.vendors') }}"
                                   class="card text-white border-0 shadow h-100 text-decoration-none rounded-4 bg-gradient-success">
                                    <div class="card-body text-center py-5">
                                        <h5 class="fw-bold fs-4">Approved Vendors</h5>
                                        <p class="opacity-90">Master list of approved vendors</p>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Logged in info -->
                <div class="col-12 mt-5">
                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-body text-center py-5">
                            <p class="fs-5 mb-2">
                                Logged in as <strong class="text-primary">{{ auth()->user()->name }}</strong>
                                <span class="badge bg-secondary ms-2">
                                    {{ auth()->user()->getRoleNames()->first() ?? 'No role' }}
                                </span>
                            </p>
                            <small class="text-muted">Use the role switcher above to test any perspective instantly.</small>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    <style>
        .bg-gradient-orange { background: linear-gradient(135deg, #f59e0b, #ea580c) !important; }
        .bg-gradient-purple { background: linear-gradient(135deg, #8b5cf6, #ec4899) !important; }
        .bg-gradient-success { background: linear-gradient(135deg, #10b981, #34d399) !important; }
        .card:hover { transform: translateY(-8px); transition: all 0.3s ease; }
    </style>
</x-app-layout>
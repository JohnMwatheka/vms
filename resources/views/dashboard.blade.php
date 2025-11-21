<x-app-layout>
    <x-slot name="header">
        <h2 class="mb-0 h3 fw-bold text-dark">Vendor Management System – Dashboard</h2>
    </x-slot>

    <div class="py-6">
        <div class="container-fluid">
            <div class="row g-5">

                <!-- DEMO ROLE SWITCHER -->
                <div class="mb-4 text-center col-12">
                    <x-role-switcher class="d-inline-block" />
                </div>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('demo_role'))
                    <div class="mb-4 text-center">
                        <span class="px-4 py-2 badge bg-primary fs-5">
                            Demo Mode: {{ ucfirst(session('demo_role')) }}
                        </span>
                    </div>
                @endif

                {{-- ======================================== --}}
                {{-- 1. INITIATOR SECTION --}}
                {{-- ======================================== --}}
                @if(auth()->user()->hasRole('initiator') || session('demo_role') === 'initiator')
                    <div class="my-5 text-center col-12">
                        <a href="{{ route('vendor.create') }}" class="px-6 py-4 shadow-lg btn btn-primary btn-lg fw-bold">
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
                            <div class="border-0 shadow-lg card rounded-4">
                                <div class="py-4 card-header bg-warning text-dark">
                                    <h4 class="mb-0 fw-bold">Pending: Send to Vendor</h4>
                                </div>
                                <div class="p-4 card-body">
                                    @foreach($pending as $vendor)
                                        <div class="p-4 mb-3 shadow-sm d-flex justify-content-between align-items-center bg-light rounded-3">
                                            <div>
                                                <h5 class="mb-1">{{ $vendor->vendor_name }}</h5>
                                                <small class="text-muted">{{ $vendor->email }}</small>
                                            </div>
                                            <form method="POST" action="{{ route('vendor.send', $vendor) }}">
                                                @csrf
                                                <button class="px-5 btn btn-success fw-bold">Send to Vendor</button>
                                            </form>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                @endif

                {{-- ======================================== --}}
{{-- 2. VENDOR PORTAL – SHOW ONLY IF EMAIL MATCHES A VENDOR IN "with_vendor" STAGE --}}
{{-- ======================================== --}}
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
            <div class="py-8 text-center text-white shadow-lg card bg-gradient-primary rounded-4">
                <div class="card-body">
                    <h2 class="mb-4 fw-bold">Welcome, {{ $vendorRecord->vendor_name ?? 'Vendor' }}!</h2>
                    <h4>Action Required</h4>
                    <p class="mb-4 fs-5">Please complete your company profile to begin the approval process.</p>
                    <a href="{{ route('vendor.portal') }}" class="px-8 py-3 btn btn-light btn-lg fw-bold">
                        Complete Profile Now
                    </a>
                </div>
            </div>
        @elseif($underReviewRecord)
            <!-- Already submitted – under review -->
            <div class="border-0 shadow-lg card rounded-4">
                <div class="py-8 text-center card-body">
                    <h4 class="text-success fw-bold">
                        Thank You! Your Profile Has Been Submitted
                    </h4>
                    <p class="mb-3 fs-5">
                        Current Stage: <strong>{{ $underReviewRecord->current_stage->label() }}</strong>
                    </p>
                    <p class="text-muted">
                        Who Acts Next: {{ $underReviewRecord->current_stage->nextActionLabel() }}
                    </p>
                    <div class="mt-4">
                        <small class="text-muted">
                            You will be notified when your profile is approved.
                        </small>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endif
                {{-- ======================================== --}}
                {{-- 3. REVIEWERS SECTION --}}
                {{-- ======================================== --}}
                @if(
                    auth()->user()->hasAnyRole(['checker', 'procurement', 'legal', 'finance', 'directors']) ||
                    in_array(session('demo_role'), ['checker', 'procurement', 'legal', 'finance', 'directors'])
                )
                    <div class="col-12">
                        <h3 class="mb-4 h4 fw-bold">Review Stages</h3>
                        <div class="row g-4">

                            @php
                                $stages = [
                                    'checker_review'     => ['Checker Review',     'bg-primary'],
                                    'procurement_review' => ['Procurement Review', 'bg-info'],
                                    'legal_review'       => ['Legal Review',       'bg-danger'],
                                    'finance_review'     => ['Finance Review',     'bg-success'],
                                    'directors_review'   => ['Directors Review',   'bg-warning text-dark'],
                                ];
                            @endphp

                            @foreach($stages as $stage => $data)
                                @php
                                    $count = \App\Models\Vendor::where('current_stage', $stage)->count();
                                @endphp

                                <div class="col-12 col-md-6 col-lg-4">
                                    <a href="{{ route('review.stage', $stage) }}"
                                       class="card text-white border-0 shadow h-100 text-decoration-none rounded-4 overflow-hidden bg-gradient {{ $data[1] }}">
                                        <div class="py-5 text-center card-body">
                                            <h5 class="fw-bold fs-4">{{ $data[0] }}</h5>
                                            <p>Click to review pending vendors</p>
                                            @if($count > 0)
                                                <span class="px-4 py-2 bg-white badge text-dark fs-6">{{ $count }} pending</span>
                                            @endif
                                        </div>
                                    </a>
                                </div>
                            @endforeach

                            <!-- Approved Vendors Card -->
                            <div class="col-12 col-md-6 col-lg-4">
                                <a href="{{ route('approved.vendors') }}"
                                   class="text-white border-0 shadow card h-100 text-decoration-none rounded-4 bg-gradient-success">
                                    <div class="py-5 text-center card-body">
                                        <h5 class="fw-bold fs-4">Approved Vendors</h5>
                                        <p>Master list of approved vendors</p>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- ======================================== --}}
                {{-- USER INFO --}}
                {{-- ======================================== --}}
                <div class="mt-5 col-12">
                    <div class="border-0 shadow-sm card rounded-4">
                        <div class="py-5 text-center card-body">
                            <p class="mb-2 fs-5">
                                Logged in as <strong class="text-primary">{{ auth()->user()->name }}</strong>
                                <span class="badge bg-secondary ms-2">
                                    {{ auth()->user()->getRoleNames()->implode(', ') ?: 'No role' }}
                                </span>
                            </p>
                            @if(session('demo_role'))
                                <small class="text-muted">Demo mode active – use role switcher to test</small>
                            @endif
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
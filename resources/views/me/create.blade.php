<x-app-layout>
    <div class="min-h-screen bg-light py-4" style="background-color: #f8f9fa;">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 col-lg-10 col-xl-8">
                    <x-role-switcher class="mb-4" />

                    <div class="card border-0 shadow-sm overflow-hidden">
                        <!-- Header Section -->
                        <div class="card-header bg-primary text-white py-4 px-3 px-md-4" 
                             style="background: linear-gradient(135deg, #0d6efd 0%, #6610f2 100%); border: none;">
                            <div class="row align-items-center">
                                <div class="col-12">
                                    <h1 class="h2 mb-2 fw-bold text-white">Create New Vendor</h1>
                                    <p class="mb-0 text-white-50 fs-6">Add a new vendor to your supplier network</p>
                                </div>
                            </div>
                        </div>

                        <!-- Form Section -->
                        <div class="card-body p-3 p-md-4 p-lg-5">
                            <form method="POST" action="{{ route('vendor.store') }}" class="needs-validation" novalidate>
                                @csrf

                                <div class="row g-3 mb-4">
                                    <!-- Vendor Name -->
                                    <div class="col-12 col-md-6">
                                        <label for="vendor_name" class="form-label fw-semibold text-dark mb-2">
                                            Vendor Name <span class="text-danger">*</span>
                                        </label>
                                        <input 
                                            type="text" 
                                            class="form-control form-control-lg border-0 shadow-sm" 
                                            style="background-color: #f8f9fa; border-radius: 12px; padding: 12px 16px;"
                                            id="vendor_name" 
                                            name="vendor_name" 
                                            required
                                            placeholder="Enter vendor name"
                                        >
                                        <div class="invalid-feedback fw-normal">
                                            Please provide a valid vendor name.
                                        </div>
                                        @error('vendor_name')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Email -->
                                    <div class="col-12 col-md-6">
                                        <label for="email" class="form-label fw-semibold text-dark mb-2">
                                            Email Address <span class="text-danger">*</span>
                                        </label>
                                        <input 
                                            type="email" 
                                            class="form-control form-control-lg border-0 shadow-sm" 
                                            style="background-color: #f8f9fa; border-radius: 12px; padding: 12px 16px;"
                                            id="email" 
                                            name="email" 
                                            required
                                            placeholder="vendor@example.com"
                                        >
                                        <div class="invalid-feedback fw-normal">
                                            Please provide a valid email address.
                                        </div>
                                        @error('email')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Phone -->
                                    <div class="col-12 col-md-6">
                                        <label for="phone" class="form-label fw-semibold text-dark mb-2">
                                            Phone Number
                                        </label>
                                        <input 
                                            type="tel" 
                                            class="form-control form-control-lg border-0 shadow-sm" 
                                            style="background-color: #f8f9fa; border-radius: 12px; padding: 12px 16px;"
                                            id="phone" 
                                            name="phone" 
                                            placeholder="+1 (555) 123-4567"
                                        >
                                        @error('phone')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Category -->
                                    <div class="col-12 col-md-6">
                                        <label for="category" class="form-label fw-semibold text-dark mb-2">
                                            Category <span class="text-danger">*</span>
                                        </label>
                                        <select 
                                            class="form-select form-select-lg border-0 shadow-sm" 
                                            style="background-color: #f8f9fa; border-radius: 12px; padding: 12px 16px; cursor: pointer;"
                                            id="category" 
                                            name="category" 
                                            required
                                        >
                                            <option value="" disabled selected>Select a category</option>
                                            <option value="Supplier">Supplier</option>
                                            <option value="Service Provider">Service Provider</option>
                                            <option value="Consultant">Consultant</option>
                                            <option value="Other">Other</option>
                                        </select>
                                        <div class="invalid-feedback fw-normal">
                                            Please select a category.
                                        </div>
                                        @error('category')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Address - Full Width -->
                                    <div class="col-12">
                                        <label for="address" class="form-label fw-semibold text-dark mb-2">
                                            Address / Location
                                        </label>
                                        <textarea 
                                            class="form-control border-0 shadow-sm" 
                                            style="background-color: #f8f9fa; border-radius: 12px; padding: 12px 16px; resize: none; min-height: 120px;"
                                            id="address" 
                                            name="address" 
                                            rows="4" 
                                            placeholder="Enter full address including street, city, state, and zip code..."
                                        >{{ old('address') }}</textarea>
                                        @error('address')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Submit Button -->
                                <div class="row mt-4 pt-4 border-top">
                                    <div class="col-12 col-md-6 d-flex align-items-center mb-3 mb-md-0">
                                        <small class="text-muted">
                                            <i class="fas fa-asterisk text-danger small"></i> Fields marked with * are required
                                        </small>
                                    </div>
                                    <div class="col-12 col-md-6 text-md-end">
                                        <button 
                                            type="submit" 
                                            class="btn btn-primary btn-lg px-5 py-3 fw-semibold border-0 shadow"
                                            style="background: linear-gradient(135deg, #0d6efd 0%, #6610f2 100%); border-radius: 12px; transition: all 0.3s ease;"
                                            onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 25px rgba(13, 110, 253, 0.3)';"
                                            onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(13, 110, 253, 0.2)';"
                                        >
                                            <i class="fas fa-plus-circle me-2"></i>
                                            Create Vendor & Notify
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap Form Validation Script -->
    <script>
        (function () {
            'use strict'
            
            var forms = document.querySelectorAll('.needs-validation')
            
            Array.prototype.slice.call(forms)
                .forEach(function (form) {
                    form.addEventListener('submit', function (event) {
                        if (!form.checkValidity()) {
                            event.preventDefault()
                            event.stopPropagation()
                        }
                        
                        form.classList.add('was-validated')
                    }, false)
                })
        })()
    </script>

    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        .form-control:focus, .form-select:focus {
            border-color: #86b7fe;
            outline: 0;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
            background-color: #ffffff;
        }
        
        .card {
            border-radius: 20px;
        }
        
        .form-control, .form-select {
            transition: all 0.3s ease;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #0d6efd 0%, #6610f2 100%);
            border: none;
        }
        
        .btn-primary:hover {
            background: linear-gradient(135deg, #0b5ed7 0%, #5a0cb3 100%);
            transform: translateY(-2px);
        }
        
        @media (max-width: 768px) {
            .card-body {
                padding: 1.5rem !important;
            }
            
            .btn-lg {
                padding: 0.75rem 1.5rem !important;
                font-size: 1rem !important;
            }
            
            .form-control-lg, .form-select-lg {
                padding: 0.75rem 1rem !important;
                font-size: 1rem !important;
            }
        }
    </style>
</x-app-layout>
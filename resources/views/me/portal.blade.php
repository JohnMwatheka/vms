<x-app-layout>
    <div class="py-4 container-fluid" style="background-color: #f8f9fa;">
        <div class="row justify-content-center">
            <div class="col-12 col-xl-10">
                <x-role-switcher />

                <div class="border-0 shadow-lg card" style="border-radius: 20px;">
                    <div class="p-4 card-body p-md-5">
                        <!-- Header Section -->
                        <div class="mb-5 row align-items-center">
                            <div class="col-12 col-md-8">
                                <h1 class="mb-2 h2 fw-bold text-dark">
                                    <i class="fas fa-user-edit me-3 text-primary"></i>
                                    Complete Your Vendor Profile
                                </h1>
                                <p class="mb-0 text-muted fs-6">Please fill in the missing information and upload supporting documents</p>
                            </div>
                            <div class="mt-3 col-12 col-md-4 text-md-end mt-md-0">
                                <span class="px-4 py-3 border-0 badge bg-warning text-dark fs-6" 
                                      style="border-radius: 50px; background: linear-gradient(135deg, #ffc107 0%, #ffb300 100%);">
                                    <i class="fas fa-clock me-2"></i>
                                    Stage: With Vendor
                                </span>
                            </div>
                        </div>

                        <!-- MAIN FORM — ONLY ONE FORM ALLOWED -->
                        <form method="POST" action="{{ route('vendor.submit') }}" class="needs-validation" novalidate>
                            @csrf
                            <input type="hidden" name="vendor_id" value="{{ $vendor->id }}">

                            <!-- Vendor Fields -->
                            <div class="mb-4 row g-4">
                                <div class="col-12 col-md-6">
                                    <label for="vendor_name" class="mb-3 form-label fw-semibold text-dark">Company Name</label>
                                    <input type="text" 
                                           class="border-0 form-control form-control-lg" 
                                           style="background-color: #f8f9fa; border-radius: 12px; padding: 12px 16px;"
                                           id="vendor_name" 
                                           value="{{ $vendor->vendor_name }}" 
                                           disabled>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label for="email" class="mb-3 form-label fw-semibold text-dark">Email</label>
                                    <input type="email" 
                                           class="border-0 form-control form-control-lg" 
                                           style="background-color: #f8f9fa; border-radius: 12px; padding: 12px 16px;"
                                           id="email" 
                                           value="{{ $vendor->email }}" 
                                           disabled>
                                </div>

                                <div class="col-12 col-md-6">
                                    <label for="phone" class="mb-3 form-label fw-semibold text-dark">
                                        Phone <span class="text-danger">*</span>
                                    </label>
                                    <input type="tel" 
                                           class="border-0 shadow-sm form-control form-control-lg" 
                                           style="background-color: #ffffff; border-radius: 12px; padding: 12px 16px;"
                                           id="phone" 
                                           name="phone" 
                                           value="{{ old('phone', $vendor->phone) }}" 
                                           required
                                           placeholder="+1 (555) 123-4567">
                                    <div class="invalid-feedback fw-normal">
                                        Please provide a valid phone number.
                                    </div>
                                    @error('phone') 
                                        <div class="mt-2 text-danger small">{{ $message }}</div> 
                                    @enderror
                                </div>

                                <div class="col-12 col-md-6">
                                    <label for="category" class="mb-3 form-label fw-semibold text-dark">
                                        Category <span class="text-danger">*</span>
                                    </label>
                                    <select name="category" 
                                            required 
                                            class="border-0 shadow-sm form-select form-select-lg" 
                                            style="background-color: #ffffff; border-radius: 12px; padding: 12px 16px; cursor: pointer;">
                                        <option value="">Select category</option>
                                        <option value="Supplier" {{ old('category', $vendor->category) == 'Supplier' ? 'selected' : '' }}>Supplier</option>
                                        <option value="Service Provider" {{ old('category', $vendor->category) == 'Service Provider' ? 'selected' : '' }}>Service Provider</option>
                                        <option value="Consultant" {{ old('category', $vendor->category) == 'Consultant' ? 'selected' : '' }}>Consultant</option>
                                        <option value="Other" {{ old('category', $vendor->category) == 'Other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                    <div class="invalid-feedback fw-normal">
                                        Please select a category.
                                    </div>
                                    @error('category') 
                                        <div class="mt-2 text-danger small">{{ $message }}</div> 
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-5">
                                <label for="address" class="mb-3 form-label fw-semibold text-dark">
                                    Full Address <span class="text-danger">*</span>
                                </label>
                                <textarea name="address" 
                                          rows="4" 
                                          required
                                          class="border-0 shadow-sm form-control" 
                                          style="background-color: #ffffff; border-radius: 12px; padding: 12px 16px; resize: none;"
                                          placeholder="Street, City, State, ZIP Code, Country">{{ old('address', $vendor->address) }}</textarea>
                                <div class="invalid-feedback fw-normal">
                                    Please provide a complete address.
                                </div>
                                @error('address') 
                                    <div class="mt-2 text-danger small">{{ $message }}</div> 
                                @enderror
                            </div>

                            <!-- SUBMIT BUTTON -->
                            <div class="pt-5 mt-5 border-top">
                                <button type="submit" 
                                        class="px-6 py-3 transition-all border-0 shadow btn btn-primary btn-lg fw-bold"
                                        style="border-radius: 12px; background: linear-gradient(135deg, #0d6efd 0%, #6610f2 100%);"
                                        onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 25px rgba(13, 110, 253, 0.4)';"
                                        onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(13, 110, 253, 0.3)';">
                                    <i class="fas fa-paper-plane me-3"></i>
                                    Submit for Review → Checker
                                </button>
                            </div>
                        </form>

                        <!-- DOCUMENT UPLOAD — SEPARATE FORM (OUTSIDE MAIN FORM) -->
                        <div class="pt-5 mt-5 border-top">
                            <h3 class="mb-3 h4 fw-bold text-dark">
                                <i class="fas fa-file-upload me-3 text-primary"></i>
                                Upload Supporting Documents
                            </h3>
                            <p class="mb-4 text-muted">PDF, JPG, PNG (max 10MB each)</p>

                            <form method="POST" action="{{ route('vendor.upload.document') }}" enctype="multipart/form-data" class="mb-5">
                                @csrf
                                <input type="hidden" name="vendor_id" value="{{ $vendor->id }}">

                                <div class="row g-3 align-items-end">
                                    <div class="col-12 col-md-4">
                                        <label for="type" class="mb-3 form-label fw-semibold text-dark">Document Type</label>
                                        <input type="text" 
                                               name="type" 
                                               class="border-0 shadow-sm form-control" 
                                               style="border-radius: 12px; padding: 12px 16px;"
                                               placeholder="e.g. Tax Certificate, NDA" 
                                               required />
                                    </div>
                                    <div class="col-12 col-md-5">
                                        <label for="document" class="mb-3 form-label fw-semibold text-dark">Select File</label>
                                        <input type="file" 
                                               name="document" 
                                               accept=".pdf,.jpg,.jpeg,.png" 
                                               required
                                               class="border-0 shadow-sm form-control" 
                                               style="border-radius: 12px; padding: 12px 16px;" />
                                        @error('document') 
                                            <div class="mt-2 text-danger small">{{ $message }}</div> 
                                        @enderror
                                    </div>
                                    <div class="col-12 col-md-3">
                                        <button type="submit" 
                                                class="py-3 transition-all border-0 shadow btn btn-success w-100 fw-semibold"
                                                style="border-radius: 12px; background: linear-gradient(135deg, #198754 0%, #20c997 100%);"
                                                onmouseover="this.style.transform='translateY(-2px)';"
                                                onmouseout="this.style.transform='translateY(0)';">
                                            <i class="fas fa-upload me-2"></i>
                                            Upload
                                        </button>
                                    </div>
                                </div>
                            </form>

                            @if($vendor->documents->count() > 0)
                                <div class="border-0 shadow-sm card bg-light">
                                    <div class="p-4 card-body">
                                        <h4 class="mb-4 h5 fw-bold text-dark">
                                            <i class="fas fa-folder-open me-3 text-primary"></i>
                                            Uploaded Documents ({{ $vendor->documents->count() }})
                                        </h4>
                                        <div class="row g-3">
                                            @foreach($vendor->documents as $doc)
                                                <div class="col-12">
                                                    <div class="bg-white border-0 shadow-sm card">
                                                        <div class="p-4 card-body">
                                                            <div class="row align-items-center">
                                                                <div class="col-12 col-md-8">
                                                                    <h6 class="mb-1 fw-bold text-dark">{{ $doc->type }}</h6>
                                                                    <p class="mb-0 text-muted small">{{ $doc->original_name }}</p>
                                                                </div>
                                                                <div class="mt-3 col-12 col-md-4 text-md-end mt-md-0">
                                                                    <a href="{{ Storage::url($doc->path) }}" 
                                                                       target="_blank" 
                                                                       class="px-4 py-2 border-0 shadow-sm btn btn-outline-primary btn-sm"
                                                                       style="border-radius: 8px; background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);">
                                                                        <i class="fas fa-eye me-2"></i>
                                                                        View Document
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endif
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
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25) !important;
            background-color: #ffffff;
        }
        
        .card {
            transition: all 0.3s ease;
        }
        
        .btn {
            transition: all 0.3s ease;
        }
        
        @media (max-width: 768px) {
            .card-body {
                padding: 1.5rem !important;
            }
            
            .btn-lg {
                padding: 1rem 2rem !important;
            }
            
            .form-control-lg, .form-select-lg {
                padding: 0.75rem 1rem !important;
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
            
            .row.g-3 {
                margin-left: -0.5rem;
                margin-right: -0.5rem;
            }
            
            .row.g-3 > [class*="col-"] {
                padding-left: 0.5rem;
                padding-right: 0.5rem;
            }
        }
    </style>
</x-app-layout>
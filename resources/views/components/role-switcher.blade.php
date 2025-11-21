<div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
    <div class="card-body p-4">
        <h3 class="card-title h5 fw-bold text-dark mb-4">
            <i class="fas fa-sync-alt me-2 text-primary"></i>
            Demo Role Switcher (1-Click)
        </h3>
        
        <div class="row g-3">
            @foreach(['initiator','vendor','checker','procurement','legal','finance','directors'] as $role)
                <div class="col-6 col-sm-3">
                    <form method="POST" action="{{ route('switch.role') }}" class="h-100">
                        @csrf
                        <input type="hidden" name="role" value="{{ $role }}">
                        <button type="submit"
                                class="w-100 h-100 p-3 border-0 rounded-3 text-decoration-none transition-all position-relative"
                                style="
                                    {{ auth()->user()->hasRole($role) 
                                        ? 'background: linear-gradient(135deg, #0d6efd 0%, #6610f2 100%); color: white; box-shadow: 0 4px 15px rgba(13, 110, 253, 0.3);' 
                                        : 'background: #f8f9fa; color: #495057; box-shadow: 0 2px 8px rgba(0,0,0,0.1);' }}
                                    transition: all 0.3s ease;
                                "
                                onmouseover="this.style.transform='translateY(-2px)'; {{ !auth()->user()->hasRole($role) ? 'this.style.backgroundColor=#e9ecef; this.style.boxShadow=0 4px 12px rgba(0,0,0,0.15);' : 'this.style.boxShadow=0 6px 20px rgba(13, 110, 253, 0.4);' }}"
                                onmouseout="this.style.transform='translateY(0)'; {{ !auth()->user()->hasRole($role) ? 'this.style.backgroundColor=#f8f9fa; this.style.boxShadow=0 2px 8px rgba(0,0,0,0.1);' : 'this.style.boxShadow=0 4px 15px rgba(13, 110, 253, 0.3);' }}">
                            
                            <div class="d-flex flex-column align-items-center justify-content-center text-center">
                                <span class="fw-semibold mb-1" style="font-size: 0.875rem;">
                                    {{ ucfirst(str_replace('_', ' ', $role)) }}
                                </span>
                                @if(auth()->user()->hasRole($role))
                                    <small class="badge bg-white text-primary mt-1 px-2 py-1" style="font-size: 0.7rem; border-radius: 12px;">
                                        <i class="fas fa-check-circle me-1"></i>Active
                                    </small>
                                @endif
                            </div>
                        </button>
                    </form>
                </div>
            @endforeach
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mt-4 border-0 shadow-sm" 
                 style="border-radius: 12px; background: linear-gradient(135deg, #d1f2eb 0%, #a8e6cf 100%);">
                <div class="d-flex align-items-center">
                    <i class="fas fa-check-circle text-success me-2 fs-5"></i>
                    <span class="text-success fw-medium">{{ session('success') }}</span>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
    </div>
</div>

<style>
    .role-switcher-btn {
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
    }
    
    .role-switcher-btn:hover {
        transform: translateY(-2px);
    }
    
    .role-switcher-btn:active {
        transform: translateY(0);
    }
    
    @media (max-width: 576px) {
        .card-body {
            padding: 1rem !important;
        }
        
        .col-6 {
            padding: 0.25rem;
        }
        
        .role-switcher-btn {
            padding: 0.75rem 0.5rem !important;
            font-size: 0.8rem;
        }
    }
</style>
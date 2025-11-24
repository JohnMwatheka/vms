<div class="mb-4 bg-transparent border-0 card" style="background: transparent !important;">
    <div class="p-4 card-body">        
        <div class="row g-2 justify-content-center">
            @foreach(['initiator','vendor','checker','procurement','legal','finance','directors'] as $role)
                <div class="col-auto">
                    <form method="POST" action="{{ route('switch.role') }}" class="d-inline">
                        @csrf
                        <input type="hidden" name="role" value="{{ $role }}">
                        <button type="submit"
                                class="p-0 transition-all bg-transparent border-0 text-decoration-none">
                            @if(auth()->user()->hasRole($role))
                                <span class="px-3 py-2 badge bg-success fs-6" style="border-radius: 12px;">
                                    <i class="fas fa-check-circle me-1"></i>{{ ucfirst(str_replace('_', ' ', $role)) }}
                                </span>
                            @else
                                <span class="px-3 py-2 badge bg-info fs-6" style="border-radius: 12px;">
                                    {{ ucfirst(str_replace('_', ' ', $role)) }}
                                </span>
                            @endif
                        </button>
                    </form>
                </div>
            @endforeach
        </div>
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
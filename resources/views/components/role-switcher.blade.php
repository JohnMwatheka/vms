<div class="bg-white rounded-lg shadow p-6 mb-8 border">
    <h3 class="text-lg font-semibold text-gray-800 mb-4">Demo Role Switcher (1-Click)</h3>
    
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
        @foreach(['initiator','vendor','checker','procurement','legal','finance','directors'] as $role)
            <form method="POST" action="{{ route('switch.role') }}" class="inline">
                @csrf
                <input type="hidden" name="role" value="{{ $role }}">
                <button type="submit"
                        class="w-full px-4 py-3 rounded-lg font-medium transition text-sm
                               {{ auth()->user()->hasRole($role) 
                                   ? 'bg-indigo-600 text-white shadow-lg' 
                                   : 'bg-gray-100 hover:bg-gray-200 text-gray-700 border' }}">
                    {{ ucfirst(str_replace('_', ' ', $role)) }}
                    @if(auth()->user()->hasRole($role)) ← Active @endif
                </button>
            </form>
        @endforeach
    </div>

    @if(session('success'))
        <div class="mt-4 p-3 bg-green-100 text-green-800 rounded-lg text-sm">
            {{ session('success') }}
        </div>
    @endif
</div>
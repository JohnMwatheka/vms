<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Approved Vendors – Masterlist</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-role-switcher />

            <form class="mb-8">
                <input type="text" name="search" placeholder="Search by name or category..."
                       class="w-full md:w-96 px-4 py-3 rounded-lg border" value="{{ request('search') }}">
            </form>

            <div class="bg-white rounded-xl shadow overflow-hidden">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left">Vendor Name</th>
                            <th class="px-6 py-4 text-left">Email</th>
                            <th class="px-6 py-4 text-left">Category</th>
                            <th class="px-6 py-4 text-left">Approved On</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($vendors as $v)
                            <tr class="border-t hover:bg-gray-50">
                                <td class="px-6 py-4">{{ $v->vendor_name }}</td>
                                <td class="px-6 py-4">{{ $v->email }}</td>
                                <td class="px-6 py-4">{{ $v->category }}</td>
                                <td class="px-6 py-4">{{ $v->approved_at?->format('d M Y') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="p-4">{{ $vendors->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>
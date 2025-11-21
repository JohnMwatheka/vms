<x-app-layout>
    <div class="max-w-5xl mx-auto py-12 px-4">
        <x-role-switcher />

        <div class="bg-white rounded-xl shadow-lg p-8">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">Complete Your Vendor Profile</h1>
                    <p class="text-gray-600 mt-2">Please fill in the missing information below</p>
                </div>
                <div class="text-right">
                    <span class="inline-block px-4 py-2 bg-amber-100 text-amber-800 rounded-full font-medium">
                        Current Stage: With Vendor
                    </span>
                </div>
            </div>

            <form method="POST" action="{{ route('vendor.submit') }}" class="space-y-8">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <x-input-label for="vendor_name" :value="__('Company Name')" />
                        <x-text-input id="vendor_name" value="{{ $vendor->vendor_name }}" disabled class="bg-gray-50" />
                    </div>

                    <div>
                        <x-input-label for="email" :value="__('Email')" />
                        <x-text-input id="email" value="{{ $vendor->email }}" disabled class="bg-gray-50" />
                    </div>

                    <div>
                        <x-input-label for="phone" :value="__('Phone *')" />
                        <x-text-input id="phone" name="phone" value="{{ old('phone', $vendor->phone) }}" required />
                        @error('phone') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <x-input-label for="category" :value="__('Category *')" />
                        <select name="category" required class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Select category</option>
                            <option value="Supplier" {{ old('category', $vendor->category) == 'Supplier' ? 'selected' : '' }}>Supplier</option>
                            <option value="Service Provider" {{ old('category', $vendor->category) == 'Service Provider' ? 'selected' : '' }}>Service Provider</option>
                            <option value="Consultant" {{ old('category', $vendor->category) == 'Consultant' ? 'selected' : '' }}>Consultant</option>
                            <option value="Other" {{ old('category', $vendor->category) == 'Other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>
                </div>

                <div>
                    <x-input-label for="address" :value="__('Full Address *')" />
                    <textarea name="address" rows="4" required
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        placeholder="Street, City, State, ZIP Code, Country">{{ old('address', $vendor->address) }}</textarea>
                    @error('address') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="pt-6 border-t">
                    <x-primary-button class="px-12 py-4 text-lg font-semibold">
                        Submit for Review → Checker
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
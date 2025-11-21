<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use App\Services\VendorWorkflowService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VendorPortalController extends Controller
{
    public function edit(VendorWorkflowService $workflow)
    {
        $vendor = Vendor::where('email', Auth::user()->email)
                        ->where('current_stage', 'with_vendor')
                        ->firstOrFail();

        return view('me.portal', compact('vendor', 'workflow'));
    }

    public function submit(Request $request, VendorWorkflowService $workflow)
    {
        $vendor = Vendor::where('email', Auth::user()->email)
                        ->where('current_stage', 'with_vendor')
                        ->firstOrFail();

        $request->validate([
            'phone'    => 'required|string',
            'address'  => 'required|string|min:10',
            'category' => 'required|in:Supplier,Service Provider,Consultant,Other',
        ]);

        $vendor->update($request->only(['phone', 'address', 'category']));

        $workflow->submitByVendor($vendor);

        return redirect()->route('dashboard')
            ->with('success', 'Your vendor profile is complete! Submitted for Checker Review.');
    }
}
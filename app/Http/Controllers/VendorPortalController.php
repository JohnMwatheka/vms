<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use App\Services\VendorWorkflowService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\VendorDocument;

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
    public function uploadDocument(Request $request)
        {
            $request->validate([
                'document' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',
                'type'     => 'required|string|max:255',
            ]);

            // Find the vendor record correctly (same as in edit() and submit())
            $vendor = Vendor::where('email', Auth::user()->email)
                            ->where('current_stage', 'with_vendor')
                            ->firstOrFail();

            // Optional: extra security (good practice)
            if ($vendor->email !== Auth::user()->email) {
                abort(403, 'Unauthorized');
            }

            $file = $request->file('document');
            $path = $file->store('vendor-documents/' . $vendor->id, 'public');

            VendorDocument::create([
                'vendor_id'      => $vendor->id,
                'path'           => $path,
                'original_name'  => $file->getClientOriginalName(),
                'type'           => $request->type,
            ]);

            return back()->with('success', 'Document uploaded successfully!');
        }
}
<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use App\Models\VendorHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VendorController extends Controller
{
    public function __invoke(Request $request)
    {
        $request->validate([
            'vendor_name' => 'required|string|max:255',
            'email'       => 'required|email|unique:vendors,email',
            'phone'       => 'nullable|string',
            'address'     => 'nullable|string',
            'category'    => 'required|in:Supplier,Service Provider,Consultant,Other',
        ]);

        $vendor = Vendor::create([
            'vendor_name'   => $request->vendor_name,
            'email'         => $request->email,
            'phone'         => $request->phone,
            'address'       => $request->address,
            'category'      => $request->category,
            'current_stage' => 'new',           // ← CORRECT: Must be 'new'
            'created_by'    => Auth::id(),
        ]);

        VendorHistory::create([
            'vendor_id'    => $vendor->id,
            'stage'        => 'new',
            'action'       => 'Created',
            'comment'      => 'Vendor record created by Initiator',
            'performed_by' => Auth::id(),
        ]);

        return redirect()->route('dashboard')
            ->with('success', 'Vendor created successfully! Now in "New" stage. Vendor sees "Awaiting Initiator".');
    }
}
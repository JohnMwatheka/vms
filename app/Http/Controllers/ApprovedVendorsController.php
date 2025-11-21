<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use Illuminate\Http\Request;

class ApprovedVendorsController extends Controller
{
    public function __invoke(Request $request)
    {
        $vendors = Vendor::approved()
            ->when($request->search, function ($q) use ($request) {
                $q->where('vendor_name', 'like', "%{$request->search}%")
                  ->orWhere('category', 'like', "%{$request->search}%");
            })
            ->with('creator')
            ->latest('approved_at')
            ->paginate(15);

        return view('approved.index', compact('vendors'));
    }
}
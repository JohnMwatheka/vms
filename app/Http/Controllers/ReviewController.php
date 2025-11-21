<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use App\Services\VendorWorkflowService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    /**
     * Display vendors in a specific review stage
     */
    public function index(Request $request, VendorWorkflowService $workflow, ?string $stage = null)
    {
        $allowedStages = [
            'checker_review',
            'procurement_review',
            'legal_review',
            'finance_review',
            'directors_review',
        ];

        // === AUTO DETECT STAGE: Real role OR demo mode ===
        if (!$stage) {
            $user = Auth::user();

            // 1. Check real Spatie roles (if assigned via seeder or login)
            if ($user && method_exists($user, 'hasRole')) {
                if ($user->hasRole('checker')) {
                    $stage = 'checker_review';
                } elseif ($user->hasRole('procurement')) {
                    $stage = 'procurement_review';
                } elseif ($user->hasRole('legal')) {
                    $stage = 'legal_review';
                } elseif ($user->hasRole('finance')) {
                    $stage = 'finance_review';
                } elseif ($user->hasRole('directors')) {
                    $stage = 'directors_review';
                }
            }

            // 2. Fallback: Demo role from your Role Switcher (session)
            if (!$stage && session('demo_role')) {
                $stage = match (session('demo_role')) {
                    'checker'     => 'checker_review',
                    'procurement' => 'procurement_review',
                    'legal'       => 'legal_review',
                    'finance'     => 'finance_review',
                    'directors'   => 'directors_review',
                    default       => null,
                };
            }
        }

        // Invalid or unknown stage → 404
        if (!$stage || !in_array($stage, $allowedStages)) {
            abort(404, 'Review stage not found');
        }

        // Query vendors in this stage (supports both string and enum in DB)
        $vendors = Vendor::with(['creator', 'histories.performer'])
            ->where('current_stage', $stage)
            ->orWhere('current_stage', \App\Enums\VendorStage::tryFrom($stage))
            ->latest()
            ->get();

        $stageLabel = ucwords(str_replace('_', ' ', $stage));

        return view('review.index', compact('vendors', 'stage', 'stageLabel', 'workflow'));
    }

    /**
     * Approve vendor → move to next stage
     */
    public function approve(Vendor $vendor, VendorWorkflowService $workflow)
    {
        $workflow->approve($vendor);

        return back()->with('success', 'Vendor approved and moved to the next stage.');
    }

    /**
     * Reject vendor → send back to vendor with comment
     */
    public function reject(Request $request, Vendor $vendor, VendorWorkflowService $workflow)
    {
        $request->validate([
            'comment' => 'required|string|min:10|max:1000',
        ]);

        $workflow->reject($vendor, $request->comment);

        return back()->with('success', 'Vendor rejected and sent back to vendor for correction.');
    }
}
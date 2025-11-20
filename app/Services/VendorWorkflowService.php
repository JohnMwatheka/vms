<?php

namespace App\Services;

use App\Enums\VendorStage;
use App\Models\Vendor;
use App\Models\VendorHistory;
use Illuminate\Support\Facades\Auth;

class VendorWorkflowService
{
    // Exact order of stages – this is the pipeline
    private array $stageOrder = [
        'new',
        'with_vendor',
        'checker_review',
        'procurement_review',
        'legal_review',
        'finance_review',
        'directors_review',
        'approved',
    ];

    // Called when Vendor clicks "Submit" in portal
    public function submitByVendor(Vendor $vendor): void
    {
        $this->moveToNextStage($vendor, 'Submitted', 'Vendor submitted completed details');
    }

    // Called by any reviewer when clicking "Approve"
    public function approve(Vendor $vendor, ?string $comment = null): void
    {
        $this->moveToNextStage($vendor, 'Approved', $comment);
    }

    // Called when reviewer clicks "Reject"
    public function reject(Vendor $vendor, string $comment): void
    {
        $currentIndex = array_search($vendor->current_stage, $this->stageOrder);
        $previousIndex = max(1, $currentIndex - 1); // Never go before "with_vendor"
        $previousStage = $this->stageOrder[$previousIndex];

        $this->logHistory($vendor, $vendor->current_stage, 'Rejected', $comment);
        $vendor->update(['current_stage' => $previousStage]);
    }

    private function moveToNextStage(Vendor $vendor, string $action, ?string $comment = null): void
    {
        $currentIndex = array_search($vendor->current_stage, $this->stageOrder);
        $nextStage = $this->stageOrder[$currentIndex + 1] ?? null;

        $this->logHistory($vendor, $vendor->current_stage, $action, $comment);

        if ($nextStage === 'approved') {
            $vendor->update([
                'current_stage' => 'approved',
                'approved_at' => now(),
            ]);
        } else {
            $vendor->update(['current_stage' => $nextStage]);
        }
    }

    private function logHistory(Vendor $vendor, string $stage, string $action, ?string $comment = null): void
    {
        VendorHistory::create([
            'vendor_id' => $vendor->id,
            'stage' => $stage,
            'action' => $action,
            'comment' => $comment,
            'performed_by' => Auth::id(),
        ]);
    }

    // Used for "Who Acts Next" label
    public function getNextActionLabel(Vendor $vendor): string
    {
        return match ($vendor->current_stage) {
            'new'              => 'Waiting for Vendor to fill details',
            'with_vendor'      => 'Vendor is completing information',
            'checker_review'   => 'Waiting for Checker',
            'procurement_review' => 'Waiting for Procurement',
            'legal_review'     => 'Waiting for Legal',
            'finance_review'   => 'Waiting for Finance',
            'directors_review' => 'Waiting for Directors',
            'approved'         => 'Fully Approved',
            'rejected'         => 'Rejected',
            default            => 'Unknown',
        };
    }

    public function getCurrentStageLabel(Vendor $vendor): string
    {
        return VendorStage::tryFrom($vendor->current_stage)?->label() ?? ucwords(str_replace('_', ' ', $vendor->current_stage));
    }
}
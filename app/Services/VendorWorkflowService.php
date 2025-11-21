<?php

namespace App\Services;

use App\Enums\VendorStage;
use App\Models\Vendor;
use App\Models\VendorHistory;
use Illuminate\Support\Facades\Auth;

class VendorWorkflowService
{
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

    public function submitByVendor(Vendor $vendor): void
    {
        $this->moveToNextStage($vendor, 'Submitted', 'Vendor submitted completed details');
    }

    public function approve(Vendor $vendor, ?string $comment = null): void
    {
        $this->moveToNextStage($vendor, 'Approved', $comment);
    }

    public function reject(Vendor $vendor, string $comment): void
    {
        $this->logHistory($vendor, $vendor->current_stage, 'Rejected', $comment);
        $vendor->update(['current_stage' => VendorStage::WithVendor]);
    }

    public function sendToVendor(Vendor $vendor): void
    {
        $this->logHistory($vendor, $vendor->current_stage, 'Sent to Vendor', 'Initiator sent record for completion');
        $vendor->update(['current_stage' => VendorStage::WithVendor]);
    }

    private function moveToNextStage(Vendor $vendor, string $action, ?string $comment = null): void
    {
        $currentValue = $this->getStageValue($vendor->current_stage);
        $currentIndex = array_search($currentValue, $this->stageOrder);
        $nextValue = $this->stageOrder[$currentIndex + 1] ?? null;

        $this->logHistory($vendor, $vendor->current_stage, $action, $comment);

        if ($nextValue === 'approved') {
            $vendor->update([
                'current_stage' => VendorStage::Approved,   // FIXED
                'approved_at'    => now(),
            ]);
        } else {
            $vendor->update([
                'current_stage' => VendorStage::tryFrom($nextValue) 
                    ?? VendorStage::from($nextValue),       // Safe fallback
            ]);
        }
    }

    private function logHistory(Vendor $vendor, string|VendorStage $stage, string $action, ?string $comment = null): void
    {
        $stageValue = $stage instanceof VendorStage ? $stage->value : $stage;

        VendorHistory::create([
            'vendor_id'     => $vendor->id,
            'stage'         => $stageValue,
            'action'        => $action,
            'comment'       => $comment,
            'performed_by'  => Auth::id(),
        ]);
    }

    private function getStageValue(string|VendorStage $stage): string
    {
        return $stage instanceof VendorStage ? $stage->value : $stage;
    }

    public function getNextActionLabel(Vendor $vendor): string
    {
        return match ($this->getStageValue($vendor->current_stage)) {
            'new'               => 'Waiting for Initiator to send',
            'with_vendor'       => 'Vendor is completing information',
            'checker_review'    => 'Waiting for Checker',
            'procurement_review'=> 'Waiting for Procurement',
             'legal_review'=> 'Waiting for Legal',
            'finance_review'    => 'Waiting for Finance',
            'directors_review'  => 'Waiting for Directors',
            'approved'          => 'Fully Approved',
            default             => 'Pending Review',
        };
    }

    public function getCurrentStageLabel(Vendor $vendor): string
    {
        $stage = $vendor->current_stage;

        return ($stage instanceof VendorStage)
            ? $stage->label()
            : (VendorStage::tryFrom($stage)?->label() ?? ucwords(str_replace('_', ' ', $stage)));
    }
}
<?php

namespace App\Enums;

enum VendorStage: string
{
    case New              = 'new';
    case WithVendor       = 'with_vendor';
    case CheckerReview    = 'checker_review';
    case ProcurementReview = 'procurement_review';
    case LegalReview      = 'legal_review';
    case FinanceReview    = 'finance_review';
    case DirectorsReview  = 'directors_review';
    case Approved         = 'approved';
    case Rejected         = 'rejected';

    /**
     * Human-readable label for UI
     */
    public function label(): string
    {
        return match ($this) {
            self::New              => 'New',
            self::WithVendor       => 'With Vendor',
            self::CheckerReview    => 'Checker Review',
            self::ProcurementReview => 'Procurement Review',
            self::LegalReview      => 'Legal Review',
            self::FinanceReview    => 'Finance Review',
            self::DirectorsReview  => 'Directors Review',
            self::Approved         => 'Approved',
            self::Rejected         => 'Rejected',
        };
    }

    /**
     * Check if current stage is one of the 5 review stages
     */
    public function isReviewStage(): bool
    {
        return in_array($this, [
            self::CheckerReview,
            self::ProcurementReview,
            self::LegalReview,
            self::FinanceReview,
            self::DirectorsReview,
        ]);
    }

    /**
     * Get the "Who Acts Next" message for dashboard cards
     */
    public function nextActionLabel(): string
    {
        return match ($this) {
            self::New              => 'Waiting for Initiator to send',
            self::WithVendor       => 'Vendor is completing information',
            self::CheckerReview    => 'Waiting for Checker',
            self::ProcurementReview => 'Waiting for Procurement',
            self::LegalReview      => 'Waiting for Legal',
            self::FinanceReview    => 'Waiting for Finance',
            self::DirectorsReview  => 'Waiting for Directors',
            self::Approved         => 'Fully Approved',
            self::Rejected         => 'Rejected',
        };
    }

    /**
     * Get the correct order of stages (used in workflow service)
     */
    public static function ordered(): array
    {
        return [
            self::New,
            self::WithVendor,
            self::CheckerReview,
            self::ProcurementReview,
            self::LegalReview,
            self::FinanceReview,
            self::DirectorsReview,
            self::Approved,
        ];
    }
}
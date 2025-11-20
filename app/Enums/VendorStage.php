<?php

namespace App\Enums;

enum VendorStage: string
{
    case New = 'new';
    case WithVendor = 'with_vendor';
    case CheckerReview = 'checker_review';
    case ProcurementReview = 'procurement_review';
    case LegalReview = 'legal_review';
    case FinanceReview = 'finance_review';
    case DirectorsReview = 'directors_review';
    case Approved = 'approved';
    case Rejected = 'rejected';

    public function label(): string
    {
        return match($this) {
            self::New => 'New',
            self::WithVendor => 'With Vendor',
            self::CheckerReview => 'Checker Review',
            self::ProcurementReview => 'Procurement Review',
            self::LegalReview => 'Legal Review',
            self::FinanceReview => 'Finance Review',
            self::DirectorsReview => 'Directors Review',
            self::Approved => 'Approved',
            self::Rejected => 'Rejected',
        };
    }

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
}
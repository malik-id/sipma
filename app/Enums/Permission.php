<?php

namespace App\Enums;

enum Permission: string
{
    case Students = 'manage-students';
    case Voters = 'manage-voters';
    case Elections = 'manage-elections';
    case Requirements = 'manage-requirements';
    case Review = 'review-registrations';
    case Candidates = 'manage-candidates';
    case Monitor = 'view-monitor';
    case Results = 'publish-results';
    case Audit = 'view-audit';
}

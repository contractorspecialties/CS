<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;

class TradeTerminology
{
    /**
     * Get the simple everyday phrasing based on the contractor's trade specialty.
     */
    public static function getLabels(?string $specialtySlug = null): array
    {
        // If no specialty is explicitly passed, try to look at the logged-in user
        if (!$specialtySlug && Auth::check() && Auth::user()->specialty) {
            $specialtySlug = Auth::user()->specialty->slug;
        }

        // Standard everyday fallback phrasing
        $dictionary = [
            'job_title' => 'Work Order',
            'job_singular' => 'Job',
            'job_plural' => 'Jobs',
            'crew_singular' => 'Crew',
            'crew_plural' => 'Crews',
            'location_singular' => 'Job Site',
            'payout_label' => 'Crew Pay',
            'step_label' => 'Required Step',
        ];

        // Swap labels based on real-world trade vocabulary
        switch ($specialtySlug) {
            case 'lawn-care':
                $dictionary['job_title'] = 'Mowing Stop';
                $dictionary['job_singular'] = 'Cut';
                $dictionary['job_plural'] = 'Cuts / Stops';
                $dictionary['crew_singular'] = 'Mowing Crew';
                $dictionary['crew_plural'] = 'Lawn Crews';
                $dictionary['location_singular'] = 'Property Address';
                $dictionary['payout_label'] = 'Trimmer / Driver Pay';
                $dictionary['step_label'] = 'Lawn Checklist Item';
                break;

            case 'house-cleaning':
                $dictionary['job_title'] = 'Cleaning Appointment';
                $dictionary['job_singular'] = 'Cleaning';
                $dictionary['job_plural'] = 'Cleanings';
                $dictionary['crew_singular'] = 'Maid Team';
                $dictionary['crew_plural'] = 'Cleaning Teams';
                $dictionary['location_singular'] = 'Home Address';
                $dictionary['payout_label'] = 'Cleaner Split';
                $dictionary['step_label'] = 'Room Checklist Item';
                break;

            case 'electrical':
            case 'plumbing':
                $dictionary['job_title'] = 'Service Call';
                $dictionary['job_singular'] = 'Dispatch Call';
                $dictionary['job_plural'] = 'Service Calls';
                $dictionary['crew_singular'] = 'Technician';
                $dictionary['crew_plural'] = 'Techs';
                $dictionary['location_singular'] = 'Service Site';
                $dictionary['payout_label'] = 'Tech Payout';
                $dictionary['step_label'] = 'Safety Check Step';
                break;
                
            case 'general-contracting':
            case 'remodeling':
                $dictionary['job_title'] = 'Construction Phase';
                $dictionary['job_singular'] = 'Phase Work';
                $dictionary['job_plural'] = 'Phases';
                $dictionary['crew_singular'] = 'Subcontractor';
                $dictionary['crew_plural'] = 'Subs / Crews';
                $dictionary['location_singular'] = 'Job Site';
                $dictionary['payout_label'] = 'Sub Contract Pay';
                $dictionary['step_label'] = 'Punch List Item';
                break;
        }

        return $dictionary;
    }

    /**
     * Helper shorthand to grab a single translated word instantly.
     */
    public static function get(string $key, ?string $specialtySlug = null): string
    {
        $labels = self::getLabels($specialtySlug);
        return $labels[$key] ?? $key;
    }
}
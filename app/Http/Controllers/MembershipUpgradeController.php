<?php

namespace App\Http\Controllers;

use App\Services\MembershipService;

class MembershipUpgradeController extends Controller
{
    public function __construct(
        private MembershipService $membershipService
    ) {}

    /**
     * Display the membership upgrade page.
     */
    public function index()
    {
        $userId = session('user_id');
        $tiers  = $this->membershipService->getTiers();

        if ($userId) {
            $memberData = $this->membershipService->getMemberData($userId);
            return view('pages.membership-upgrade', array_merge(
                ['isGuest' => false, 'tiers' => $tiers],
                $memberData
            ));
        }

        return view('pages.membership-upgrade', [
            'isGuest'     => true,
            'tiers'       => $tiers,
            'user'        => null,
            'currentTier' => $tiers[0],
            'nextTier'    => $tiers[1],
            'progressPct' => 0,
            'orderCount'  => 0,
            'totalSpent'  => 0,
            'freeshipCount' => 0,
        ]);
    }
}

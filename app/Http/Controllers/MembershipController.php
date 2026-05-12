<?php

namespace App\Http\Controllers;

use App\Services\MembershipService;

class MembershipController extends Controller
{
    public function __construct(
        private MembershipService $membershipService
    ) {}

    /**
     * Display the membership page.
     * Shows public tier info for guests, personalised dashboard for logged-in users.
     */
    public function index()
    {
        $userId = session('user_id');

        if ($userId) {
            $memberData = $this->membershipService->getMemberData($userId);
            return view('pages.membership', array_merge(['isGuest' => false], $memberData));
        }

        return view('pages.membership', [
            'isGuest'      => true,
            'tiers'        => $this->membershipService->getTiers(),
            'user'         => null,
            'currentTier'  => null,
            'nextTier'     => null,
            'progressPct'  => 0,
            'orderCount'   => 0,
            'totalSpent'   => 0,
            'freeshipCount'=> 0,
        ]);
    }
}

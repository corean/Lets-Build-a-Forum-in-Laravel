<?php

namespace App\Http\Controllers;

use App\Thread;
use App\User;
use Illuminate\Http\Request;


class ProfileController extends Controller
{
    /**
     * @param User $user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(User $user)
    {
        return view('profiles.show', [
            'profileUser' => $user,
            'activities'  => $this->getActivities($user)
        ]);
    }
    
    /**
     * @param User $user
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection
     */
    protected function getActivities(User $user)
    {
        return $user->activity()
                    ->latest()
                    ->with('subject')
                    ->take(50)
                    ->get()
                    ->groupBy(function ($activity) {
                        return $activity->created_at->format('Y-m-d');
                    });
    }
    
}

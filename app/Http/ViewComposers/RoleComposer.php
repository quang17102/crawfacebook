<?php

namespace App\Http\ViewComposers;

use App\Models\UserRole;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class RoleComposer
{
    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $view->with('userRoles', UserRole::where('user_id', Auth::id())->pluck('role')->toArray() ?? []);
    }
}

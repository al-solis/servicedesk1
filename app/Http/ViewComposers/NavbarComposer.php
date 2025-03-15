<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use App\Models\TicketHeader;

class NavbarComposer
{
    public function compose(View $view)
    {
        $user = Auth::user();
        $tickets = TicketHeader::with(['details', 'user'])
            ->where('status', 'Open')
            ->orderBy('date_created', 'desc')
            ->get(); // Ensure you fetch the tickets

        // Share data with the navbar view
        $view->with(compact('tickets', 'user'));
    }
}

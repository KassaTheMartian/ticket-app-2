<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});


// // routes/channels.php
// Broadcast::channel('ticket.{ticket_id}', function ($user, $ticket_id) {
//     // Allow access if user is admin or customer of the ticket
//     $ticket = \App\Models\Ticket::find($ticket_id);
//     return ($user->id === $ticket->customer_id && get_class($user) === 'App\Models\Customer') || 
//            (get_class($user) === 'App\Models\User');
// });

// channels.php
Broadcast::channel('ticket.{ticket_id}', function ($user, $ticket_id) {
    $ticket = \App\Models\Ticket::find($ticket_id);
    
    if (!$ticket) {
        return false;
    }

    // Check if user is a customer
    if (get_class($user) === 'App\Models\Customer') {
        return $user->id === $ticket->customer_id;
    }
    
    // If user is admin/staff (App\Models\User)
    return get_class($user) === 'App\Models\User';
});
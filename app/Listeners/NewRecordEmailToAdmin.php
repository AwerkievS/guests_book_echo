<?php

namespace App\Listeners;

use App\Events\NewRecord;
use App\Mail\NewRecordMail;
use App\Repository\Role;
use App\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class NewRecordEmailToAdmin implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(NewRecord $event)
    {
        $user = User::where('role_id', Role::ADMIN_ROLE_ID)->firstOrFail();
        Mail::to($user->email)->send(new NewRecordMail($event->record->id));
    }
}

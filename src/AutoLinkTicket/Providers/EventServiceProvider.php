<?php

namespace Modules\AutoLinkTicket\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\AutoLinkTicket\Services\LinkTicketService;

class EventServiceProvider extends ServiceProvider
{
    public function boot()
    {
        \Eventy::addFilter('thread.body_output', function ($content, $thread) {
            return LinkTicketService::convertTicketNumbersToLinks($content);
        }, 1000, 2);

        // JS, das FreeScouts processLinks()-target=_blank für Ticket-Links korrigiert
        \Eventy::addFilter('javascripts', function ($javascripts) {
            $javascripts[] = \Module::getPublicPath('autolinkticket') . '/js/module.js';
            return $javascripts;
        });

        parent::boot();
    }
}

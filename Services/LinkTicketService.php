<?php

namespace Modules\AutoLinkTicket\Services;

class LinkTicketService
{
    public static function convertTicketNumbersToLinks($content)
    {
        return preg_replace_callback(
            '/#(\d+)/',
            function ($matches) {
                $ticketId = $matches[1];
                $url = "/conversation/" . $ticketId;
                return "<a href=\"{$url}\">#{$ticketId}</a>";
            },
            $content
        );
    }
}

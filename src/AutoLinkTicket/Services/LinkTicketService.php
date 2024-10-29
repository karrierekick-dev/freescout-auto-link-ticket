<?php

namespace Modules\AutoLinkTicket\Services;

use App\Conversation;

class LinkTicketService
{
    public static function convertTicketNumbersToLinks($content)
    {
        return preg_replace_callback(
            '/#(\d+)/',
            function ($matches) {
                $ticketId = $matches[1];
                $title = "";

                try {
                    $conversation = Conversation::find($ticketId);
                    if ($conversation) {
                        $title = $conversation->subject;
                    }
                }
                catch (\Exception $e) {}

                $url = "/conversation/" . $ticketId;

                $link = "<a href=\"{$url}\"";
                if ($title)
                    $link .= " title=\"{$title}\"";

                $link .= ">#{$ticketId}</a>";
                return $link;

            },
            $content
        );
    }
}

<?php

namespace Modules\AutoLinkTicket\Services;

use App\Conversation;

class LinkTicketService
{
    public static function convertTicketNumbersToLinks($content)
    {
        return preg_replace_callback(
            '/\B#(\d+)\b(?![^\s>]*")/', // (?![^\s>]*") is a negative lookahead to ensure the # symbol isn’t part of an HTML attribute containing # followed by a number within quotes.
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

                $link .= " target=\"_self\">#{$ticketId}</a>";
                return $link;
            },
            $content
        );
    }
}

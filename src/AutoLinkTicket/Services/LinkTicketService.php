<?php

namespace Modules\AutoLinkTicket\Services;

use App\Conversation;

class LinkTicketService
{
    public static function convertTicketNumbersToLinks($content)
    {
        return preg_replace_callback(
            // (?<![&:]) verhindert:
            // - CSS-Farben wie "color:#333333"
            // - HTML-Entities wie "&#252;" (ü), die FreeScout seit 1.8.230
            //   via HTMLPurifier Core.EscapeNonASCIICharacters erzeugt
            '/(?<![&:])\B#(\d+)\b(?![^\s>]*")/',
            function ($matches) {
                $ticketId = $matches[1];
                $title = "";

                try {
                    $conversation = Conversation::find($ticketId);
                    if ($conversation) {
                        $title = $conversation->subject;
                    }
                } catch (\Exception $e) {}

                $url = "/conversation/" . $ticketId;

                $link = "<a href=\"{$url}\"";
                if ($title)
                    $link .= " title=\"" . htmlspecialchars($title, ENT_QUOTES, 'UTF-8') . "\"";

                $link .= " target=\"_self\">#{$ticketId}</a>";
                return $link;
            },
            $content
        );
    }
}

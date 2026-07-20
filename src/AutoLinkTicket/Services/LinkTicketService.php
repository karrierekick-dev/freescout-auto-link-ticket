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
                $ticketNumber = (int) $matches[1];
                $title = "";
                $conversationId = $ticketNumber;

                try {
                    // Bei custom_number ist die angezeigte Ticketnummer ≠ DB-ID
                    $field = Conversation::numberFieldName();
                    $conversation = Conversation::where($field, $ticketNumber)->first();
                    if ($conversation) {
                        $title = $conversation->subject;
                        $conversationId = $conversation->id;
                    }
                } catch (\Exception $e) {}

                $url = "/conversation/" . $conversationId;

                $link = "<a href=\"{$url}\"";
                if ($title)
                    $link .= " title=\"" . htmlspecialchars($title, ENT_QUOTES, 'UTF-8') . "\"";

                // data-target: FreeScout setzt in processLinks() alle Links auf target=_blank
                $link .= " target=\"_self\" data-target=\"_self\">#{$ticketNumber}</a>";
                return $link;
            },
            $content
        );
    }
}

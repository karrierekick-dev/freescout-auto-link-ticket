<?php

namespace Modules\AutoLinkTicket\Services;

use App\Conversation;

class LinkTicketService
{
    const OPTION_PATTERNS = 'autolinkticket.patterns';
    const DEFAULT_PATTERNS = '#';

    public static function convertTicketNumbersToLinks($content)
    {
        return preg_replace_callback(
            self::buildPattern(),
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
                $link .= " target=\"_self\" data-target=\"_self\">" . htmlspecialchars($matches[0], ENT_QUOTES, 'UTF-8') . "</a>";
                return $link;
            },
            $content
        );
    }

    /**
     * Builds the detection regex from the configured trigger patterns (e.g. "#", "Case", "XYZ-").
     *
     * Patterns starting with a word character ("Case", "XYZ-") are bounded via \b; a space
     * before the number is optional, so both "Case 123" and "XYZ1234" match (a desired
     * separator such as "-" is simply part of the pattern itself, e.g. "XYZ-" for "XYZ-1234").
     * Patterns starting with a non-word character ("#") behave like the original: \B instead
     * of \b, because \b would falsely trigger on "#" for CSS colors ("color:#333333") or HTML
     * entities ("&#252;", emitted since FreeScout 1.8.230 via HTMLPurifier
     * Core.EscapeNonASCIICharacters); (?<![&:]) additionally excludes those cases explicitly.
     */
    private static function buildPattern()
    {
        $alternatives = array_map(function ($prefix) {
            $quoted = preg_quote($prefix, '/');

            if (preg_match('/^\w/', $prefix)) {
                return '\b' . $quoted . '\s*';
            }

            return '(?<![&:])\B' . $quoted;
        }, self::getPrefixes());

        return '/(?:' . implode('|', $alternatives) . ')(\d+)\b(?![^\s>]*")/i';
    }

    private static function getPrefixes()
    {
        $raw = \Option::get(self::OPTION_PATTERNS, self::DEFAULT_PATTERNS);
        $prefixes = array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', (string) $raw)));

        return $prefixes ?: [self::DEFAULT_PATTERNS];
    }
}

<?php

namespace Modules\AutoLinkTicket\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\AutoLinkTicket\Services\LinkTicketService;

class EventServiceProvider extends ServiceProvider
{
    const MODULE_ALIAS = 'autolinkticket';

    public function boot()
    {
        $this->loadViewsFrom(__DIR__ . '/../Resources/views', self::MODULE_ALIAS);

        \Eventy::addFilter('thread.body_output', function ($content, $thread) {
            return LinkTicketService::convertTicketNumbersToLinks($content);
        }, 1000, 2);

        // JS, das FreeScouts processLinks()-target=_blank für Ticket-Links korrigiert
        \Eventy::addFilter('javascripts', function ($javascripts) {
            $javascripts[] = \Module::getPublicPath('autolinkticket') . '/js/module.js';
            return $javascripts;
        });

        $this->registerSettings();

        parent::boot();
    }

    private function registerSettings()
    {
        \Eventy::addFilter('settings.sections', function ($sections) {
            $sections[self::MODULE_ALIAS] = ['title' => __('Auto Link Ticket'), 'icon' => 'link', 'order' => 610];
            return $sections;
        }, 40);

        \Eventy::addFilter('settings.section_settings', function ($settings, $section) {
            if ($section != self::MODULE_ALIAS) {
                return $settings;
            }

            $settings[LinkTicketService::OPTION_PATTERNS] = \Option::get(
                LinkTicketService::OPTION_PATTERNS,
                LinkTicketService::DEFAULT_PATTERNS
            );

            return $settings;
        }, 20, 2);

        \Eventy::addFilter('settings.view', function ($view, $section) {
            if ($section != self::MODULE_ALIAS) {
                return $view;
            }

            return self::MODULE_ALIAS . '::settings';
        }, 20, 2);
    }
}

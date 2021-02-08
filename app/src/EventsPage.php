<?php

namespace MelissaWu\DiscoverEvents;

use Page;

/**
 * events page for:
 * 1. host events list
 * 2. show detail of each event
 * 3. host the Attend Form allow user to submit the booking to attend the event
 * 
 */
class EventsPage extends Page
{
    private static $has_many = [
        'Events' => Event::class
    ];
    private static $owns = [
        'Events'
    ];
}
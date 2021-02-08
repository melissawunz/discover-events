<?php

namespace MelissaWu\DiscoverEvents;

use SilverStripe\ORM\DataObject;

/**
 * used to categorise the event
 */
class EventCategory extends DataObject
{
    private static $db = [
        'Title' => 'Varchar(50)'
    ];

    private static $belongs_many_many = [
        'Events' => Event::class
    ];
}
<?php

namespace MelissaWu\DiscoverEvents;

use SilverStripe\Forms\NumericField;
use SilverStripe\ORM\DataObject;

/**
 * event location detailed information
 */
class EventLocation extends DataObject
{
    private static $db = [
        'City' => 'Varchar(30)',
        'Suburb' => 'Varchar',
        'Address' => 'Varchar',
        'Alias'=> 'Varchar',
        'Longitude' => 'Double(25, 20)',
        'Latitude' => 'Double(25, 20)'
    ];

    private static $has_many = [
        'Events' => Event::class
    ];

    private static $summary_fields = [
        'City' => 'City',
        'Location' => 'Location',
        'Alias' => 'Alias',
    ];

    /**
     * Create a Location field on the EventLocation object,
     * it combines the location parts to form the complete location information   
     * 
     * @return string
     */
    public function getLocation()
    {
        $location = $this->Address. ', '  . $this->Suburb. ', '  . $this->City;
        if($this->Alias) {
            return $location = $this->Alias. ', ' . $location;
        }
        return $location;
    }

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();
        $fields->addFieldsToTab('Root.Main', [
            NumericField::create('Longitude', 'Longitude')->setScale(20),
            NumericField::create('Latitude', 'Latitude')->setScale(20)
        ]);
        return $fields;
    }
}
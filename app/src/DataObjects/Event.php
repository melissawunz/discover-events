<?php

namespace MelissaWu\DiscoverEvents;

use SilverStripe\AssetAdmin\Forms\UploadField;
use SilverStripe\ORM\DataObject;
use SilverStripe\Assets\Image;
use SilverStripe\Forms\DropdownField;
use SilverStripe\Forms\ListboxField;
use SilverStripe\Versioned\Versioned;

/**
 * Event class, ...
 */
class Event extends DataObject
{
    private static $db = [
        'Title' => 'Varchar',           // title of the event
        'Description' => 'HTMLText',    // detailed description of the event
        'EventDate' => 'Date',          // the date when the event happens
        'StartTime' => 'Time',          // the start time of the event
        'EndTime' => 'Time'             // the end time of the event
    ];

    private static $has_one = [
        'Photo' => Image::class,
        'Location' => EventLocation::class,
        'EventsPage' => EventsPage::class
    ];

    private static $many_many = [
        'Categories' => EventCategory::class,
        'Hosts' => Host::class
    ];

    private static $summary_fields = [
        'Title',
        'EventDate',
        'StartTime.Nice',
        'EndTime.Nice',
        'Description.FirstSentence',
        'HostNames'
    ];

    private static $field_labels = [
        'EventDate' => 'Date',
        'StartTime.Nice' => 'Start at',
        'EndTime.Nice'=> 'End at',
        'Description.FirstSentence' => 'Summary',
        'HostNames' => 'Hosted by'
    ];

    private static $extensions = [
        Versioned::class
    ];

    
    private static $owns = [
        'Photo'
    ];
    
    private static $versioned_gridfield_extensions = true;

    /**
     * Link of every Event instance
     * 
     * @return string
     */
    public function Link()
    {
        return $this->EventsPage()->Link('detail/'.$this->ID);
    }

    public function getHostNames()
    {
        $hosts = $this->Hosts();
        $hostNames = '';
        foreach($hosts as $host){
            $hostNames .= $host->FirstName. ' ' . $host->LastName . ', ';
        }
        if(isset($hostNames)) {
            return rtrim($hostNames, ', ');
        }
        return $hostNames;
    }

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();
        $fields->addFieldsToTab('Root.Main', [
            UploadField::create('Photo', 'Photo')->setFolderName('event-photos'),
            DropdownField::create('LocationID', 'Location', EventLocation::get()->map('ID', 'Alias')->toArray(), 'Select location'),
            ListboxField::create('Hosts', 'Hosted by', Host::get()->map('ID', 'Name')->toArray(), 'Select hosts'),
            ListboxField::create('Categories', 'Categories', EventCategory::get()->map('ID', 'Title')->toArray()),
        ]);
        return $fields;
    }

}
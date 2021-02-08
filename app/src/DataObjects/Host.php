<?php

namespace MelissaWu\DiscoverEvents;

use GraphQL\Examples\Blog\Type\Field\HtmlField;
use SilverStripe\AssetAdmin\Forms\UploadField;
use SilverStripe\ORM\DataObject;
use SilverStripe\Assets\Image;
use SilverStripe\Forms\EmailField;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\RequiredFields;
use SilverStripe\Forms\TextareaField;
use SilverStripe\Forms\TextField;
use SilverStripe\ORM\FieldType\DBField;
use SilverStripe\Versioned\Versioned;

/**
 * detailed information of host(s) for each event
 */
class Host extends DataObject
{
    private static $db = [
        'FirstName' => 'Varchar',
        'LastName' => 'Varchar',
        'Email' => 'Varchar',
        'Bio' => 'HTMLText' // biography of the host
    ];

    private static $has_one = [
        'Profile' => Image::class
    ];

    private static $belongs_many_many = [
        'Events' => Event::class
    ];

    private static $extensions = [
        Versioned::class
    ];

    private static $owns = [
        'Profile'
    ];

    private static $versioned_gridfield_extensions = true;

    private static $summary_fields = [
        'Name' => 'Name',
        'Email' => 'Email',
        'Profile.CMSThumbnail' => 'Profile'
    ];

    /**
     * Create a Name filed on the Host object, and cast it to the DBField type
     * 
     * @return DBField
     */
    public function getName()
    {
        return DBField::create_field('Varchar', $this->FirstName . ' '. $this->LastName);
    }

    public function getCMSFields()
    {
        $fields = FieldList::create([
            TextField::create('FirstName', 'FirstName *')->addExtraClass('required'),
            TextField::create('LastName', 'LastName *')->addExtraClass('required'),
            EmailField::create('Email', 'Email *')->addExtraClass('required'),
            TextareaField::create('Bio', 'Bio')->addExtraClass('required'),
            $upload = UploadField::create('Profile', 'Profile')
        ]);
        $upload->setFolderName('host-profiles');
        return $fields;
    }

    /**
     * CMS validator, requires fields of FirstName, LastName, and Email to be filled
     * 
     * @return Validator 
     */
    public function getCMSValidator()
    {
        return new RequiredFields([
            'FirstName',
            'LastName',
            'Email'
        ]);
    }
}
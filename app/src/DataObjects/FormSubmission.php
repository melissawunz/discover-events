<?php

namespace MelissaWu\DiscoverEvents;

use SilverStripe\Control\Email\Email;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\LiteralField;
use SilverStripe\Forms\ReadonlyField;
use SilverStripe\Forms\TextField;
use SilverStripe\ORM\ArrayList;
use SilverStripe\ORM\DataObject;
use SilverStripe\View\ArrayData;

/**
 * Used to record the form submission and send emails to users
 * 
 */
class FormSubmission extends DataObject
{
    private static $db = [
        'URL' => 'Varchar(255)',
		'Payload' => 'Text',
		'UniqueID' => 'Text'
    ];

    private static $has_one = array(
		'Origin' => 'DataObject'
	);

    private static $summary_fields = [
        'Created' => 'Created',
        'URL' => 'URL',
		'UniqueID' => 'UniqueID'
    ];

    /**
     * generate an unique ID for every submission
     * 
     */
    function onBeforeWrite(){
		$this->UniqueID = $this->CreateUniqueID($this->Created);
		parent::onBeforeWrite();
	}
    
    /**
	 * Create unique ID for the submission
	 *
	 * @param $str string | string to hash
	 *
	 * @return string | hashed string
	 **/
	function CreateUniqueID($str){
		return md5($str . microtime());
    }
    
    /**
     * Send emails responding to the submission
     * 
     */
    public function SendEmails()
    {
        $data = json_decode($this->Payload);

        // @todo set up the from email in Site Config
        $from = 'melissawu328@gmail.com';

        $event = $data->Event;
        $attendee = $data->Attendee;
        $location = $data->Location;

        $subject = 'Thanks for booking to attend ' . $event->Title;
        $to = $attendee->Email;
        $toName = $attendee->FirstName . ' ' . $attendee->LastName;
        $emailData = ArrayData::create([
            'FirstName' => $attendee->FirstName, 
            'Title' => $event->Title,
            'Date' => $event->EventDate,
            'StartTime' => $event->StartTime,
            'EndTime' => $event->EndTime,
            'Location' => $location
        ]);
        $body = $emailData->renderWith('MelissaWu/DiscoverEvents/Emails/AttendEventEmail')->Plain();
        // hand over the email information to sendgrid to send it
        $email = new EmailSendGrid($from, $to, $subject, $body, 'MelissaWu', $toName);
        $email->send();
    }
}
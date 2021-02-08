<?php

namespace MelissaWu\DiscoverEvents;

use PageController;
use SilverStripe\Control\HTTPRequest;
use SilverStripe\Core\Environment;
use SilverStripe\Forms\EmailField;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\Form;
use SilverStripe\Forms\FormAction;
use SilverStripe\Forms\LiteralField;
use SilverStripe\Forms\RequiredFields;
use SilverStripe\Forms\TextareaField;
use SilverStripe\Forms\TextField;
use SilverStripe\View\Requirements;

class EventsPageController extends PageController
{
    private static $allowed_actions = [
        'index',
        'detail',     // show the event detail
        'attend',     // show the attending event form
        'success',     // render the page after successfully submit the form

        'AttendForm'    // AttendForm
    ];

    /**
     * index action: include React code and the related css file for the Events Page
     * 
     * @param HTTPRequest $request
     * @return array
     */
    public function index(HTTPRequest $request){
        Requirements::javascript('production/eventspage/index.min.js');
        Requirements::css('production/eventspage/index.min.css');
        return [];
    }

    /**
     * detail action: render the detailed event information on the page
     * 
     * @param HTTPRequest $request
     * 
     * @return array
     */
    public function detail(HTTPRequest $request)
    {
        // get the session
        $session = $request->getSession();
        if($session->get('EventID')) {
            $eventID = $session->get('EventID');
        } 

        $params = $request->allParams();
        if($params['ID']) {
            $eventID = $params['ID'];
            $session->set('EventID', $eventID);
        }
        
        if(!$eventID)
        {
            return $this->redirect($this->Link().'events');
        }

        $event = Event::get()->byID($eventID);
        $event->AttendLink = $this->Link('attend/' . $event->ID);
        return [
            'Event' => $event
        ];
    }

    /**
     * attend action: render the form page for user to register the event
     * 
     * @param HTTPRequest $request
     * 
     * @return array
     */
    public function attend(HTTPRequest $request)
    {
        // get the session
        $session = $request->getSession();
        if($session->get('EventID')) {
            $eventID = $session->get('EventID');
        }

        $params = $request->allParams();
        if($params['ID']) {
            $eventID = $params['ID'];
            $session->set('EventID', $eventID);
        }

        if(!$eventID)
        {
            return $this->redirect($this->Link().'events');
        }
        $event = Event::get()->byID($eventID);
        return [
            'Form' => $this->AttendForm(),
            'Event' => $event
        ];
    }

    /**
     * attend form construction
     * 
     * @return Form
     */
    public function AttendForm()
    {
        $fields = FieldList::create();
        $fields->push(TextField::create('FirstName', 'FirstName*')->setAttribute('placeholder', 'Enter first name'));
        $fields->push(TextField::create('LastName', 'LastName*')->setAttribute('placeholder', 'Enter last name'));
        $fields->push(EmailField::create('Email', 'Email*')->setAttribute('placeholder', 'Enter email'));
        $fields->push(TextareaField::create('Message', 'Message'));

        $recaptcha_key = Environment::getEnv('GOOGLE_RECAPTCHA_SITE_KEY');
        $fields->push(LiteralField::create('html', '<div class="g-recaptcha mb-3" data-theme="light" data-sitekey="' . $recaptcha_key . '"></div>'));
        $actions = FieldList::create(
            LiteralField::create("back", '<a href="'.$this->Link(). '" class="btn btn-link mr-4">Back</a>'),
            FormAction::create("handleAttend", "Submit")->addExtraClass('btn btn-primary')
        );

        $form = Form::create(
            $this,
        	'AttendForm',
        	$fields,
            $actions,
            new RequiredFields(['FirstName', 'LastName', 'Email' ])
        );
        
        if ($sessionData = $this->request->getSession()->get("FormInfo.AttendForm.data")){
        	$form->loadDataFrom($sessionData);
        }

        return $form;
    }

    /**
     * handle the form submission:
     * 1. record the form submission
     * 2. Send the email to attendee
     * 
     * @param array(POST date) $data
     * @param Form $form
     *
     */
    public function handleAttend($data, $form)
    {
        //Google ReCaptcha verification
        $secret = Environment::getEnv('GOOGLE_RECAPTCHA_SECRET_KEY');
        $recaptcha = new \ReCaptcha\ReCaptcha($secret);
		$resp = $recaptcha->setExpectedHostname($_SERVER['SERVER_NAME'])
					  ->verify($data['g-recaptcha-response'], $this->getRequest()->getIP());
        // form submission record
        if($resp->isSuccess()){
            // get the Event object in session
            $session = $this->request->getSession();
            $session->set("FormInfo.{$form->getName()}.data", $data);

            $data = $this->finalizeData($session->get('EventID'), $data);

            $submission = FormSubmission::create();
            $submission->URL = $this->getRequest()->getURL();
            $submission->Payload = json_encode($data);
            $submission->write();
    
            // send the email to attendee
            $submission->SendEmails();
            $session->clearAll();
            return $this->redirect($this->Link().'success');
        } else{
            return $this->redirect($this->Link().'attend');
        }
    }

    /**
     * format the data for sending email
     * 
     * @param string $eventID
     * @param array $attendee
     */
    private function finalizeData($eventID, $attendee)
    {
        $event = Event::get()->byID($eventID);
        $hosts = [];
        foreach($event->Hosts() as $host){
            $host->photo = $host->Profile->FileFilename;
            array_push($hosts, $host->toMap());
        } 

        return array_merge(
            ['Attendee' => $attendee], 
            ['Event' => $event->toMap()], 
            ['Location' => $event->Location->getLocation()],
            ['Hosts' => $hosts]
        );
    }
}
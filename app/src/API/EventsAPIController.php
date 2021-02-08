<?php

namespace MelissaWu\DiscoverEvents;

use SilverStripe\Control\Controller;
use SilverStripe\Control\HTTPRequest;
use SilverStripe\ORM\Map;
use SilverStripe\ORM\Queries\SQLSelect;

/**
 * This Controller works as an API endpoint serve front-end requests
 */
class EventsAPIController extends Controller
{

    private static $allowed_actions = [
        'feedEvents',
        'feedCities'
    ];

    private static $url_handlers = [
        'events/$City/$Between/$To' => 'feedEvents',
        'cities' => 'feedCities'
    ];

    // @todo: restrict access to this api by validating api key
    // public function init()
    // {
            // parent::init();
    // }

    /**
     * @api events/City/Between/To
     * Handle the events api request to return a list of events information
     * 
     * @param HTTPRequest $request
     * 
     * @return HTTPResponse
     */
    public function feedEvents(HTTPRequest $request)
    {
        $eventsList = Event::get();
        $allParams = $request->allParams();
        if ($allParams['City'] != 'All') {
            $eventsList = $eventsList->leftJoin(
                "MelissaWu_DiscoverEvents_EventLocation", 
                "\"MelissaWu_DiscoverEvents_EventLocation\".\"ID\" = \"MelissaWu_DiscoverEvents_Event\".\"LocationID\""
                )->filter([
                    'City' => $allParams['City']
                ]);
        }
        if ($allParams['Between'] != -1 && $allParams['To'] != -1) {
            $eventsList = $eventsList->filter([
                'EventDate:GreaterThanOrEqual' => $allParams['Between'],
                'EventDate:LessThanOrEqual' => $allParams['To']
            ]);
        }

        $events = [];
        foreach ($eventsList as $event) {
            array_push($events, [
                'ID' => $event->ID,
                'Title' => $event->Title,
                'Description' => $event->Description,
                'Photo' => $event->Photo->getFilename(),
                'EventDate' => $event->EventDate,
                'StartTime' => $event->StartTime,
                'EndTime' => $event->EndTime,
                'Location' => $event->Location->Alias,
                'Link' => $event->Link()
            ]);
        }
        $events = json_encode($events);

        $this->getResponse()->addHeader("Content-type", "application/json");
        $this->getResponse()->addHeader('Access-Control-Allow-Origin', "*");
        $this->getResponse()->addHeader("Access-Control-Allow-Methods", "GET");
        $this->getResponse()->addHeader("Access-Control-Allow-Headers", "x-requested-with");
        $this->getResponse()->setBody($events);
        return $this->getResponse();
    }

    /**
     * @api cities
     * Handle the citys api request to return a list of citys
     * 
     * @param HTTPRequest $request
     * 
     * @return HTTPResponse
     */
    public function feedCities(HTTPRequest $request)
    {
        $query = SQLSelect::create()->setFrom('MelissaWu_DiscoverEvents_EventLocation')->setSelect('DISTINCT(City)');
        $cities = $query->execute()->column();
        $result = [];
        foreach ($cities as $city) {
            array_push($result, [
                'value' => $city,
                'label' => $city
            ]);
        }
        $result = json_encode($result);
        $this->getResponse()->addHeader("Content-type", "application/json");
        $this->getResponse()->addHeader('Access-Control-Allow-Origin', "*");
        $this->getResponse()->addHeader("Access-Control-Allow-Methods", "GET");
        $this->getResponse()->addHeader("Access-Control-Allow-Headers", "x-requested-with");
        $this->getResponse()->setBody($result);
        return $this->getResponse();
    }
}

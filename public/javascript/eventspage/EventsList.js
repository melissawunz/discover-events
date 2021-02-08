import React from 'react';
import EventItem from './EventItem';
import GeneralFilter from './GeneralFilter';

import * as DataRequest from './DataRequest';

class EventsList extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            events: [],
            cities: [],
            filters: {
                between: new Date(),
                to: new Date(),
                city: 'All'
            }
        };
        this.handleCityFilter = this.handleCityFilter.bind(this);
    }

    componentDidMount() {
        this.handleFirstRender();
    }

    async handleFirstRender(){
        let events = await DataRequest.getEventList();
        let cities = await DataRequest.getCityList();
        this.setState({
            events,
            cities
        });
    }

    async handleCityFilter(selectedCity) {
        const {between, to} = this.state.filters;
        let city = '';
        if(selectedCity){
            city = selectedCity.value;
        } else {
            city = 'All';
        }
        const filters = {city, between, to};
        let events = await DataRequest.getEventList(city);
        this.setState({
            filters,
            events
        });
    }

    render() {
        let events = this.state.events.map((event) => (
            <a href={event.Link}>
                <EventItem detail={event} key={event.eventID} />
            </a>
        ));
        return (
            <div>
                <div className="filter-section">
                    <div className="label">Filter:</div>
                    <div className="from-filter">
                        {/* <img className="calendar-icon" alt="" src={CalendarIcon}></img> */}
                        {/* <CalendarFilter label="from" handleChange={this.handleDateFilter} date={this.state.filters.from} isDisabled={disableCalendar}/> */}
                    </div>
                    <div className="to-filter">
                        {/* <img className="calendar-icon" alt="" src={CalendarIcon}></img> */}
                        {/* <CalendarFilter label="to" handleChange={this.handleDateFilter} date={this.state.filters.to} isDisabled={disableCalendar}/> */}
                    </div>
                    <div className="location-filter">
                        {/* <img className="calendar-icon" alt="" src={LocationIcon}></img> */}
                        <GeneralFilter options={this.state.cities} handleClick={this.handleCityFilter} selectedValue={selectedValue} placeHolder="Select City"/>
                    </div>
                </div>
                <div className="events-list">
                    {events}
                </div>
            </div>
        );
    }
}

export default EventsList;
import React from 'react';
import EventItem from './EventItem';

import * as DataRequest from './DataRequest';

class EventList extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            events: []
        };
    }

    componentDidMount() {
        this.handleFirstRender();
    }

    async handleFirstRender(){
        let events = await DataRequest.getEventList();
        this.setState({
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
            <div className="events-list">
                {events}
            </div>
        );
    }
}

export default EventList;
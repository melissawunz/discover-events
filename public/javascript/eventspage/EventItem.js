import React from 'react';

function EventItem(props) {
    return (
        <div className="event-card" key={props.key}>
            <div className="photo">
                {props.detail.Photo ? (<img alt={props.detail.Title} src={require(`../../assets/${props.detail.Photo}`)}></img>) : <img alt={props.detail.Title} src={require("../../assets/event-photos/events.jpg")}></img>}
                
            </div>
            <div className="brief">
                <div className="datetime">{props.detail.EventDate} {props.detail.StartTime} to {props.detail.EndTime}</div>
                <div className="title">{props.detail.Title}</div>
                <div className="location">{props.detail.Location}</div>
            </div>
        </div>
    );
}

export default EventItem;
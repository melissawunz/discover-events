const apiURL = 'http://localhost/api';

export const getEventList = (city = 'All', between = -1, to = -1) => fetch(
    `${apiURL}/events/${city}/${between}/${to}`
  ).then(res => res.json());

export const getCityList = () => fetch(
  `${apiURL}/cities/`
).then(res => res.json());
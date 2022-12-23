const getOrganizingEvents = () => {
    sendAjaxRequest('get', `api/myEvents/organizing`, null, getOrganizingEventsHandler);
}

function getOrganizingEventsHandler() {
    const events = JSON.parse(this.responseText)
    const area = document.getElementById("myeventsarea")
    area.innerHTML = events
}

const getMyEvents = (hasPassed) => {
    sendAjaxRequest('post', `api/myEvents/onMyAgenda`, { 'hasPassed': hasPassed }, getMyEventsHandler);
}

function getMyEventsHandler() {
    const events = JSON.parse(this.responseText)
    const area = document.getElementById("myeventsarea")
    area.innerHTML = events
}

const getFormsCreateEvent = (hasPassed) => {
    sendAjaxRequest('get', `api/myEvents/createEvent`, { 'hasPassed': hasPassed }, getFormsCreateEventHandler);
}

function getFormsCreateEventHandler() {
    const form = JSON.parse(this.responseText)
    const area = document.getElementById("myeventsarea")
    area.innerHTML = form
}
const getOrganizingEvents = () => {
    sendAjaxRequest('get', `api/myEvents/organizing`, null, getOrganizingEventsHandler);

    removeAlerts()
    createAlert('info', "Please wait while we load the events you're organizing...")
}

function getOrganizingEventsHandler() {
    const events = JSON.parse(this.responseText)
    const area = document.getElementById("myeventsarea")
    area.innerHTML = events

    /* Rerun flowbite's modal code the event listeners are added again*/
    initModal(selectors)
    removeAlerts()
    createAlert('success', 'Done! Here are the events you requested!')
}

const getMyEvents = (hasPassed) => {
    sendAjaxRequest('post', `api/myEvents/onMyAgenda`, { 'hasPassed': hasPassed }, getMyEventsHandler);

    removeAlerts()
    if(hasPassed)
        createAlert('info', 'Please wait while we load your past events...')
    else
        createAlert('info', 'Please wait while we load your future events...')
}

function getMyEventsHandler() {
    const events = JSON.parse(this.responseText)
    const area = document.getElementById("myeventsarea")
    area.innerHTML = events

    /* Rerun flowbite's modal code the event listeners are added again*/
    initModal(selectors)
    removeAlerts()
    createAlert('success', 'Done! Here are the events you requested!')
}

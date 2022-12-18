if (window.location.pathname.includes('/event/')) {
    eventid = window.location.pathname.split('/event/')[1].split('/')[0]
    localStorage.setItem("eventid", eventid)
    
    if (window.location.pathname.includes('/manage'))
        setgoBackBtn()
}

function setgoBackBtn() {
    select('#goback').href = 'http://' + window.location.host + '/myEvents' + '/organizing'
}

function setEventToPublic(eventid = localStorage.getItem("eventid")) {
    const url = '/event/'+ eventid + '/public';
    sendAjaxRequest('put', url, null, visibilityHandler(true, eventid));
}

function setEventToPrivate(eventid = localStorage.getItem("eventid")) {
    const url = '/event/'+ eventid + '/private';
    sendAjaxRequest('put', url, null, visibilityHandler(false, eventid));
}


function cancelEvent(eventid = localStorage.getItem("eventid")) {
    const url = '/event/'+ eventid + '/cancel';
    sendAjaxRequest('put', url, null, cancelEventHandler(eventid));
}

function visibilityHandler(public, eventid) {
    if (this.status == 403) {
        window.location = '/login';
        return;
    }

    /* Deal with errors */

    if (public) 
        createAlert('success', 'You have set this event visibility to public successfully.')
    else
        createAlert('success', 'You have set this event visibility to private successfully.')
        
}

function cancelEventHandler(eventid) {
    if (this.status == 403) {
        window.location = '/login';
        return;
    }

    /* Deal with errors */

    createAlert('success', 'You have canceled this event successfully.')       
}


const radioPrivate = select('#helper-radio-private')
const radioPublic = select('#helper-radio-public')

radioPrivate.addEventListener('change', function (e) {
    if (this.checked) {
        setEventToPrivate()
    }
});

radioPublic.addEventListener('change', function (e) {
    if (this.checked) {
        setEventToPublic()
    }
});


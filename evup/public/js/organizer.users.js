if (window.location.pathname.includes('/event/') && window.location.pathname.includes('/adduser')) {
    eventid = localStorage.getItem("eventid")
    if (!eventid) {
        eventid = window.location.pathname.split('/event/')[1].split('/')[0]
        localStorage.setItem("eventid", eventid)
    }
    setgoBackBtn()
}

function setgoBackBtn() {
    eventid = localStorage.getItem("eventid")
    select('#goback').href = 'http://' + window.location.host + '/event/' + eventid + '/attendees'
}

function addUser(userid, eventid = localStorage.getItem("eventid")) {
    const url = '/event/'+ eventid + '/adduser/' + userid;
    sendAjaxRequest('post', url, null, userHandler(true, userid));
}

function removeUser(eventid, userid) {
    const url = '/event/'+ eventid + '/removeuser/' + userid;
    sendAjaxRequest('post', url, null, userHandler(false, userid));
}

function userHandler(add, userid) {
    if (this.status == 403) {
        window.location = '/login';
        return;
    }

    /* Deal with errors */

    if(add) {
        elem = select('#addBtn-' + userid)
        while(elem.nodeName !== "TR")
            elem = elem.parentNode;
        
        elem.remove()
    }
    else {
        elem = select('#removeBtn-' + userid)
        while(elem.nodeName !== "TR")
            elem = elem.parentNode;
        
        elem.remove()
    }

    /*
    if (add) 
        createAlert('success', 'You have added this user successfully.')
    else
        createAlert('success', 'You have removed this user successfully.')
    */

}
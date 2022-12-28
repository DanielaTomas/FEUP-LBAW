if (window.location.pathname.includes('/event/')) {
    eventid = window.location.pathname.split('/event/')[1].split('/')[0]
    localStorage.setItem("eventid", eventid)
    
    if (window.location.pathname.includes('/adduser'))
        setgoBackBtn()
    
    if (window.location.pathname.includes('/attendees')) {
        setAddUserBtn()
        setgoBackToEventBtn()
    }

    if (window.location.pathname.includes('/dashboard')) 
        setAddUserBtn()

}

function setgoBackBtn() {
    select('#goback').href = 'http://' + window.location.host + '/event/' + eventid + '/dashboard'
}

function setgoBackToEventBtn() {
    select('#goback').href = 'http://' + window.location.host + '/event/' + eventid
}

function setAddUserBtn() {
    if (select('#adduser'))
        select('#adduser').href = select('#adduser').href.replace('id', eventid)
    else
        return
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

    if (add) 
        createAlert('success', 'You have added this user successfully.')
    else
        createAlert('success', 'You have removed this user successfully.')
        
}
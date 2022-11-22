const leaveEvent = (eventid) => {
    sendAjaxRequest('post', `myEvents/${eventid}`, { 'eventid': eventid }, leaveEventHandler(eventid));
}

function leaveEventHandler(eventid) {

    const event = document.getElementById("eventCard" + eventid)
    event.remove()
}

    
const inviteUser = (eventid) => {
    const mysearch = document.getElementById("mySearch");
    sendAjaxRequest('post', `myEvent/${eventid}/invite`, { 'search': mysearch.value }, inviteUserHandler);
}

function inviteUserHandler(ev) {
    const res = JSON.parse(this.responseText);
    if (res.status == 404) {
        alert("USER not found")
    }else if (res.status == 400){
        alert("already invited")
    }else if (res.status == 200){
        alert("YAY")
    }
    
}

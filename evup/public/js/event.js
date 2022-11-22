const leaveEvent = (eventid) => {
    sendAjaxRequest('post', `myEvents/${eventid}`, { 'eventid': eventid }, leaveEventHandler(eventid));
}

function leaveEventHandler(eventid) {

    const event = document.getElementById("eventCard" + eventid)
    event.remove()
}

const inviteUser = (eventid) => {
    const mysearch = document.getElementById("mySearch");
    sendAjaxRequest('post', `myEvent/${eventid}/invite`, { 'search': mysearch.value }, inviteUserHandler(eventid));
}

function inviteUserHandler(eventid) {
    
    if (this.status == 'Not found') {
        console.log("HIIIIIIIIIII")
        alert("USER not found")
    }else if (this.status == 400){
        alert("already invited")
    }else if (this.status == 200){
        alert("YAY")
    }
    
}

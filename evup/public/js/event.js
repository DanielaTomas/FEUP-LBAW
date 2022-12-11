const leaveEvent = (eventid) => {
    sendAjaxRequest('post', `myEvents/${eventid}`, { 'eventid': eventid }, leaveEventHandler(eventid));
}

function leaveEventHandler(eventid) {
    const event = document.getElementById("eventCard" + eventid)
    event.remove()
}

const event_id = window.location.pathname.substring(7);

const search = document.getElementById("mySearch");
search.addEventListener("keyup",function(){
    sendAjaxRequest('post', `/event/${event_id}/searchUsers`, { 'search': search.value,'eventid':event_id }, searchUserHandler);
})

function searchUserHandler(){
    const users = JSON.parse(this.responseText)
    const area = document.getElementById("userResults")
    area.innerHTML = users
}


function inviteUser(userid){
    const event_id2 = window.location.pathname.substring(7)
    const email = select("#email-" + userid).textContent
 
    sendAjaxRequest('post', `/event/${event_id}/inviteUsers`, { 'email': email, 'eventid':event_id2 });
    const card = select("#usercard-" + userid)
    card.remove()
}


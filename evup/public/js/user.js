
const askOrganizer = (userid) => {
    sendAjaxRequest('get', `${userid}/organizerRequest`, null, askOrganizerHandler(userid));
}

function askOrganizerHandler(userid){
    const request = document.getElementById("organizerRequestButton")
    const div= document.getElementById("orgRequest")
    const pending = document.createElement('div')
    pending.className = "block text-white bg-gray-700  font-medium rounded-lg text-sm px-5 py-2.5 text-center"
    pending.id = "pending{{ $user->userid }}"
    pending.title = "You have already submitted a request be an Organizer"
    pending.innerHTML=`Organizer Request Pending`
    div.append(pending)
    request.remove()

    createAlert('success', 'You have requested to be an organizer! Our administrators will review your request soon.')
}

const acceptInvite = (inviteid) => {
    sendAjaxRequest('post', `/user/accept/${inviteid}`, { 'inviteid': inviteid }, acceptInviteHandler(inviteid));
}

const declineInvite = (inviteid) => {
    sendAjaxRequest('post', `/user/deny/${inviteid}`, { 'inviteid': inviteid }, declineInviteHandler(inviteid));
}

function acceptInviteHandler(eventid) {
    const invite1 = document.getElementById("accept" + eventid)
    invite1.remove()
    const invite2 = document.getElementById("decline" + eventid)
    invite2.remove()
    const newButtonA = document.createElement('td');
    newButtonA.innerHTML =`
    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
    <span class="relative inline-block px-3 py-1 font-semibold text-green-900 leading-tight">
            <span aria-hidden class="absolute inset-0 bg-green-200 opacity-50 rounded-full"></span>
            <span class="relative">Aceite</span>
    </span>
</td>`
    const space=document.getElementById("here"+eventid)
    space.append(newButtonA)

}

function declineInviteHandler(eventid) {
    const invite = document.getElementById("accept" + eventid)
    invite.remove()
    const invite2 = document.getElementById("decline" + eventid)
    invite2.remove()
    const newButtonD = document.createElement('td');
    newButtonD.innerHTML =`
    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
    <span class="relative inline-block px-3 py-1 font-semibold text-red-900 leading-tight">
            <span aria-hidden class="absolute inset-0 bg-red-200 opacity-50 rounded-full"></span>
            <span class="relative">Rejeitado</span>
    </span>
</td>`
    const space=document.getElementById("here"+eventid)
    space.append(newButtonD)

}
const requestToJoin = (eventid) => {
    sendAjaxRequest('post', `/api/requestToJoin`, { 'eventid': eventid }, requestToJoinHandler(eventid));
}

function requestToJoinHandler(eventid) {
    button = select('#requestToJoinButton' + eventid)
    button.remove()
}
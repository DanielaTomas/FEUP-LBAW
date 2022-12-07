
const askOrganizer = (userid) => {
    sendAjaxRequest('get', `${userid}/organizerRequest`, { 'userid': userid }, askOrganizerHandler(userid));
}

function askOrganizerHandler(userid){
    const request = document.getElementById("botao")
    const diva= document.getElementById("request"+userid)
    const pending = document.createElement('button')
    pending.innerHTML=`
            <button id="pending{{ $user->userid }}" class="block text-white bg-gray-700 hover:bg-gray-800 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-gray-600 dark:hover:bg-gray-700 dark:focus:ring-gray-800" type="button">
                Pending
            </button>
        `
    diva.append(pending)
    request.remove()
    
    
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
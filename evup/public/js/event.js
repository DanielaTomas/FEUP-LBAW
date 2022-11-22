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
    const users = JSON.parse(this.responseText);
    const area = document.getElementById("userResults")
    area.innerHTML=" "
    for(const user of users){
        const card = document.createElement('div');
        card.innerHTML = `
        <div class="w-full p-2 max-w-sm bg-white border border-gray-200 rounded-lg shadow-md dark:bg-gray-800 dark:border-gray-700">
            <div class="flex flex-col items-center pb-10">
                <img class="w-24 h-24 mb-3 rounded-full shadow-lg" src="${user.userphoto}" alt="Bonnie image"/>
                <h5 class="mb-1 text-xl font-medium text-gray-900 dark:text-white">${user.name}</h5>
                <span class="text-sm text-gray-500 dark:text-gray-400">${user.email}</span>
                <div id="class="flex mt-4 space-x-3 md:mt-6">
                    <button onclick="inviteUser()" class="inline-flex items-center px-4 py-2 text-sm font-medium text-center text-white bg-gray-900 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Invite User</button>
                </div>
            </div>
        </div>
        `
        area.appendChild(card)
    }
}


function inviteUser(){
    const event_id2 = window.location.pathname.substring(7)
    const email = event.target.parentElement.parentElement.children[2].textContent
 
    sendAjaxRequest('post', `/event/${event_id}/inviteUsers`, { 'email': email, 'eventid':event_id2 });
    const card = event.target.parentElement.parentElement.parentElement
    card.remove()
}




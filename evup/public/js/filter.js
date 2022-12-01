


const filterTag = (tagid) => {
    sendAjaxRequest('post', `/filter_tag`, { 'tagid': tagid }, filterTagHandler);
    //const tag = event.target
    /*
    if (tag.class === "text-xs inline-flex items-center font-bold leading-sm uppercase px-3 py-1 bg-indigo-400 text-blue-700 rounded-full"){
        console.log("YEE")
        tag.className  = "text-xs inline-flex items-center font-bold leading-sm uppercase px-3 py-1 bg-indigo-900 text-blue-700 rounded-full"
    }
    else{
        console.log("AAAAAAAAAAAAI")
        tag.className = "text-xs inline-flex items-center font-bold leading-sm uppercase px-3 py-1 bg-indigo-400 text-blue-700 rounded-full"
    }*/
}

function filterTagHandler() {
    const events = JSON.parse(this.responseText)
    console.log("events:" + events.length)
    const area = document.getElementById("homeEvents")
    area.innerHTML = " "
    for (const event of events) {
        const card = document.createElement('div');
        card.className = "flex flex-col w-full bg-white rounded shadow-lg sm:w-3/4 md:w-1/2 lg:w-2/5"
        card.innerHTML = `
    
    <div class="w-full h-64 bg-top bg-cover rounded-t"
        style="background-image:  url( ${event.eventphoto})">
    </div>
    <div class="flex flex-col w-full md:flex-row">
        <div
            class="flex flex-row justify-around p-4 font-bold leading-none text-gray-800 uppercase bg-gray-400 rounded md:flex-col md:items-center md:justify-center md:w-1/4">
            <div class="md:text-xl"> ${event.startdate} </div>
        </div>
        <div class="p-4 font-normal text-gray-800 md:w-3/4">
            <a href="event/${event.eventid}"><h1 class="mb-4 text-4xl font-bold leading-none tracking-tight text-gray-800">${event.eventname }</h1></a>
            <div id=eventCardCategories> @each('partials.category', $event->eventcategories()->get(), 'category') </div>
            <p class="leading-normal">${event.description}</p>
            <div class="flex flex-column items-center mt-4 ">
                <div class="w-1/2 text-gray-700">  ${event.eventaddress } </div>
                <div id=eventCardTags> @each('partials.tag', $event->eventTags()->get(), 'tag') </div>

                <button id="eventCardJoinRequest"><a href="event/${event.eventid}"> request to Join</a></button>
            </div>
        </div>
    </div>

        `
        area.appendChild(card)
    }
}
const voteOption = (optionid,pollid) => {
    sendAjaxRequest('get', `/event/${optionid}/answerpoll`, null, voteOptionHandler(optionid,pollid));
}

function voteOptionHandler(optionid,pollid){
    const allvotes=document.getElementById("TotalVotes" + pollid)
    const poll=document.getElementById("poll"+pollid)
    const options=poll.querySelectorAll("div.option")
    const beforeoptions=poll.querySelectorAll("div.beforeOption")
    console.log(beforeoptions)
    allvotes.textContent=parseInt(allvotes.textContent)+1
    for(var i = 0; i < options.length; i++) {
        const botao=options[i].querySelector("input")
        const votos=options[i].querySelector("p")
        if(botao.checked){
            votos.textContent=parseInt(votos.textContent)+1
        }
        botao.disabled=true
        const numero=document.createElement("div")
        numero.innerHTML=`
            <span class="text-sm font-medium text-blue-700 dark:text-white"></span>
        `
        numero.textContent=Math.round(parseInt(votos.textContent)/parseInt(allvotes.textContent)*100,2)+"%"
        options[i].append(numero)
        const barra=document.createElement("div")
        barra.innerHTML=`
        
                    <div id="bar" class="bg-blue-600 h-2.5 rounded-full"></div>
      
        `
        barra.classList.add("bg-gray-200")
        barra.classList.add("rounded-full")
        barra.classList.add("w-full")
        barra.classList.add("dark:bg-gray-700")
        const innerbarra=barra.querySelector("div")
        innerbarra.style.width=Math.round(parseInt(votos.textContent)/parseInt(allvotes.textContent)*100,2)+"%"
        beforeoptions[i].append(barra)
    }
}


function createPoll(){
    const createpollbutton=document.getElementById("CreatePoll")
    const addhereform=document.getElementById("addhereform")
    if(addhereform!=null){ 
        addhereform.style.display='none'
        createpollbutton.addEventListener('click',function(){
            addhereform.style.display='block'
            createpollbutton.style.display='none'
            addOption()
        })
    }
    
}


createPoll()

function addOption(){
    const addOption=document.getElementById("addOption")
    const addHere=document.getElementById("addHere")
     var i=2
     addOption.addEventListener('click',function(){
        i++
        const pending = document.createElement('div')
        pending.innerHTML= `
        <input class="shadow appearance-none border border-red-500 rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline" name=option[] type="text" required>`
        addHere.prepend(pending)
        if(i==4){
            addOption.style.display='none'
        }
     })
}

const leaveEvent = (eventid) => {
    sendAjaxRequest('post', '/api/myEvents/leave_event', { 'eventid': eventid }, leaveEventHandler(eventid));
}

function leaveEventHandler(eventid) {
    if(window.location.pathname==='/myEvents'){
        const event = document.getElementById("eventCard" + eventid)
        event.remove()
    }else{
       window.location.reload()
    }
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


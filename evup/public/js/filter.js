


const filterTag = (tagid) => {
    sendAjaxRequest('post', `/api/filter_tag`, { 'tagid': tagid }, filterTagHandler);
    const tag = event.target
    
    if (tag.classList.contains("bg-indigo-900") ){
        console.log("YEE")
    
    }
    else{
        console.log("AAAAAAAAAAAAI")

    }
}

function filterTagHandler() {
    const events = JSON.parse(this.responseText)
    const area = document.getElementById("homeEvents")
    area.innerHTML = events
}
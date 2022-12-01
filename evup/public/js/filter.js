


function filterTag(tagid){
    sendAjaxRequest('post', `/filter_tag`, {'tagid': tagid}, filterTagHandler());
    const tag = event.target
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

function filterTagHandler(){
    //const events = JSON.parse(this.responseText)
    //console.log("events:"+events.length)
}
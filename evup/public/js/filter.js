
const filterTag = (tagid) => {
    sendAjaxRequest('post', `/api/filter_tag`, { 'tagid': tagid }, filterTagHandler);
}

function filterTagHandler() {
    const events = JSON.parse(this.responseText)
    const area = document.getElementById("homeEvents")
    area.innerHTML = events
}

const filterTag = (tagid) => {
    sendAjaxRequest('post', `/api/filter_tag`, { 'tagid': tagid }, filterTagHandler);
}

function filterTagHandler() {
    const events = JSON.parse(this.responseText)
    const area = document.getElementById("homeEvents")
    area.innerHTML = events
}

const filterCategory = (categoryid) => {
    sendAjaxRequest('post', `/api/filter_category`, { 'categoryid': categoryid }, filterCategoryHandler);
}

function filterCategoryHandler() {
    const events = JSON.parse(this.responseText)
    const area = document.getElementById("homeEvents")
    area.innerHTML = events
}
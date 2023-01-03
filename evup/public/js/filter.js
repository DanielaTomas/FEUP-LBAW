
const filterTag = (tagid) => {
    sendAjaxRequest('post', `/api/filter_tag`, { 'tagid': tagid }, filterTagHandler);

    removeAlerts()
    createAlert('info', "Please wait while we load the events you're looking for...")
}

function filterTagHandler() {
    const events = JSON.parse(this.responseText)
    const area = document.getElementById("homeEvents")
    area.innerHTML = events

    removeAlerts()
    createAlert('success', 'Done! Here are the events you requested!')
}

const filterCategory = (categoryid) => {
    sendAjaxRequest('post', `/api/filter_category`, { 'categoryid': categoryid }, filterCategoryHandler);

    removeAlerts()
    createAlert('info', "Please wait while we load the events you're looking for...")
}

function filterCategoryHandler() {
    const events = JSON.parse(this.responseText)
    const area = document.getElementById("homeEvents")
    area.innerHTML = events

    removeAlerts()
    createAlert('success', 'Done! Here are the events you requested!')
}
function closeReport(reportid) {
    const url = '/admin/reports/'+ reportid + '/close';
    sendAjaxRequest('put', url, null, reportHandler(true, reportid = reportid));
}

function delEvent(reportid, eventid) {
    const url = '/admin/events/'+ eventid + '/delete';
    sendAjaxRequest('put', url, null, reportHandler(false, reportid, eventid));
}

function reportHandler(close, reportid, eventid = null) { // if close is true, act as a close report, else act as a delete event
    if (this.status == 403) {
        window.location = '/login';
        return;
    }

    /* Deal with errors */

    empty = false

    if(close) {
        closeDiv = select('#close-' + reportid).parentElement
        reportstatus = select('#reportstatus-' + reportid)
        container = closeDiv.parentElement

        if (closeDiv.parentElement.children.length === 1) empty = true
        closeDiv.remove()

        reportstatus.innerHTML = 'Closed'
        reportstatus.classList = 'bg-green-200 text-green-600 py-1 px-3 rounded-full text-xs'
    }
    else {
        confirmbtn = select('[onclick="delEvent(' + reportid + ', ' + eventid + ')"]')
        realid = (confirmbtn.id).split('-')[1]
        delDiv = select('#del-' + realid).parentElement
        eventstatus = select('#eventstatus-' + reportid + eventid)
        container = delDiv.parentElement

        if (delDiv.parentElement.children.length === 1) empty = true
        delDiv.remove()

        eventstatus.innerHTML = 'Canceled'
        eventstatus.classList = 'bg-red-200 text-red-600 py-1 px-3 rounded-full text-xs'
    }

    if (empty)
        container.innerHTML = '<p>Request Reviewed</p>'
    
    if (close) 
        createAlert('success', 'You have closed this report successfully.')
    else
        createAlert('success', 'You have deleted this event successfully.')

}
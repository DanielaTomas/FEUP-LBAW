function acceptOrgReq(id) {
    const url = '/admin/organizer_requests/'+ id + '/accept';
    sendAjaxRequest('put', url, null, orgReqHandler(true, id));
}

function denyOrgReq(id) {
    const url = '/admin/organizer_requests/'+ id + '/deny';
    sendAjaxRequest('put', url, null, orgReqHandler(false, id));
}

function orgReqHandler(accept, id) { // if close is true, act as a close report, else act as a delete event
    if (this.status == 403) {
        window.location = '/login';
        return;
    }

    /* Deal with errors */
    requeststatus = select('#requeststatus-' + id)
    div = select('#acceptOR-' + id).parentElement

    div.innerHTML = '<p>Request Reviewed</p>'

    if (accept)  {
        requeststatus.innerHTML = 'Accepted'
        requeststatus.classList = 'bg-green-200 text-green-600 py-1 px-3 rounded-full text-xs'

        createAlert('success', 'You have  accepted this organizer request successfully.')
    }
    else {
        requeststatus.innerHTML = 'Denied'
        requeststatus.classList = 'bg-red-200 text-red-600 py-1 px-3 rounded-full text-xs'

        createAlert('success', 'You have denied this organizer request successfully.')
    }

}
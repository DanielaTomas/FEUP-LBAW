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
    button = select('#acceptOR-' + id)
    div = select('#acceptOR-' + id).parentElement

 
    div.innerHTML = '<p>Request Reviewed</p>'

    if (accept) 
        createAlert('success', 'You have  accepted this organizer request successfully.')
    else
        createAlert('success', 'You have denied this organizer request successfully.')

}
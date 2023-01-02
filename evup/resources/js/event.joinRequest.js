function acceptJoinReq(eventid, id) {
    const url = '/event/' + eventid + '/join_requests/' + id + '/accept';
    sendAjaxRequest('put', url, null, joinReqHandler(true, id));
}

function denyJoinReq(eventid, id) {
    const url = '/event/' + eventid + '/join_requests/' + id + '/deny';
    sendAjaxRequest('put', url, null, joinReqHandler(false, id));
}

function joinReqHandler(accept, id) { // if accept is true, act as a accept request, else act as a deny request
    if (this.status == 403) {
        window.location = '/login';
        return;
    }

    /* Deal with errors */
    requeststatus = select('#requeststatus-' + id)
    div = select('#acceptJOIN-' + id).parentElement

    div.innerHTML = '<p>Request Reviewed</p>'

    if (accept)  {
        requeststatus.innerHTML = 'Accepted'
        requeststatus.classList = 'bg-green-200 text-green-600 py-1 px-3 rounded-full text-xs'

        createAlert('success', 'You have  accepted this join request successfully.')
    }
    else {
        requeststatus.innerHTML = 'Denied'
        requeststatus.classList = 'bg-red-200 text-red-600 py-1 px-3 rounded-full text-xs'

        createAlert('success', 'You have denied this join request successfully.')
    }

}
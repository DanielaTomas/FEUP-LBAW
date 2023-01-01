const createNewComment = (eventid) => {
    const url = '/event/'+ eventid + '/createComment/';
    const body = select('#commentTextArea').value;
    if (!body) return;

    sendAjaxRequest('post', url, { 'commentcontent': body, 'eventid': eventid }, newCommentHandler(false));
}

const createNewReply = (parent, eventid, parentid) => {
    const url = '/event/'+ eventid + '/createComment/' + parentid;
    const body = select(`#replyTextArea-${parentid}`).value;
    if (!body) return;

    sendAjaxRequest('post', url, { 'commentcontent': body, 'eventid': eventid, 'parentid': parentid }, newCommentHandler(true, parent, 'afterend', `#replyTextArea-${parentid}`));
}

const newCommentHandler = (reply, parent = select('#comments'), position = 'afterbegin', textarea = '#commentTextArea') => function () {
    const json = JSON.parse(this.responseText);

    if (this.status != 200) {
        createAlert('error',json.errors);
    }
    if (reply) {
        createAlert('success','You have added a new reply successfully.');
    }
    else {
        createAlert('success','You have added a new comment successfully.');
    }
    parent.insertAdjacentHTML(position, json.html);
    select(textarea).value = '';
    
    /* Rerun flowbite's modal code the event listeners are added again*/
    initModal(selectors)
}

/*
const editComment = (commentId, editBox) => {
    const body = select(`#edit_textarea_${commentId}`).value;
    if (!body) return;

    sendAjaxRequest('PUT', `/comment/${commentId}`, { body }, editCommentHandler(commentId, editBox));
}
*/

const updateComment = (eventid, commentid) => {
    const url = '/event/'+ eventid + '/editComment/' + commentid;
    const body = select(`#editCommentInput-${commentid}`).value;
    if (!body) return;

    sendAjaxRequest('post', url, { 'commentcontent': body, 'eventid': eventid, 'commentid': commentid }, editCommentHandler(body,commentid));
}

function editCommentHandler(body,commentid) {
    
    let element = document.getElementById('content-' + commentid);

    element.innerHTML = body;

    createAlert('success', 'You have edited your comment successfully.')
}

const deleteComment = (eventid,commentid) => {
    const url = '/event/'+ eventid + '/delete/' + commentid;
    sendAjaxRequest('post', url, { 'eventid': eventid, 'commentid' : commentid }, deleteCommentHandler(commentid));
}

function deleteCommentHandler(commentid) {
    const comment = document.getElementById('comment' + commentid)
    const child = document.querySelectorAll('#child' + commentid)

    comment.remove()        

    for(var i = 0; i < child.length; i++) {
        child[i].remove()        
    }

    createAlert('success', 'You have removed this comment successfully.')
}

var hasVoted = new Array(500).fill(false);

const like = (id,commentid,voted) => {
    if(!voted && !hasVoted[commentid]) {
        const url = '/event/'+ id + '/like/' + commentid + '/voted/' + voted;
        sendAjaxRequest('post', url, { 'eventid': id, 'commentid' : commentid }, likeHandler(commentid));
    }
    else {
        createAlert('warning','You already voted on this comment');
        alert('You already voted on this comment');
    }
}

function likeHandler(commentid) {
    let count = document.querySelector("#likeCount-" + commentid);
    count.innerHTML++;
    hasVoted[commentid] = true;
}  

const dislike = (id,commentid, voted) => {
    if(!voted && !hasVoted[commentid]) {
        const url = '/event/'+ id + '/dislike/' + commentid + '/voted/' + voted;
        sendAjaxRequest('post', url, { 'eventid': id, 'commentid' : commentid }, dislikeHandler(commentid));
    }
    else {
        createAlert('warning','You already voted on this comment');
        alert('You already voted on this comment');
    }
}

function dislikeHandler(commentid) {
    let count = document.querySelector("#dislikeCount-" + commentid);
    count.innerHTML++;
    hasVoted[commentid] = true;
}   
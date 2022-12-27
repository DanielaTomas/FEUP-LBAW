const createNewComment = (eventid) => {
    const url = '/event/'+ eventid + '/createComment/';
    const body = select('#commentTextArea').value;
    if (!body) return;

    sendAjaxRequest('post', url, { 'commentcontent': body, 'eventid': eventid }, newCommentHandler());
}


const editComment = (commentId, editBox) => {
    const body = select(`#edit_textarea_${commentId}`).value;
    if (!body) return;

    sendAjaxRequest('PUT', `/comment/${commentId}`, { body }, editCommentHandler(commentId, editBox));
}

const newCommentHandler = () => function () {
    const json = JSON.parse(this.responseText);

    if (this.status != 200) {
        createAlert('error',json.errors);
    }

    createAlert('success','You have added a new comment successfully.');
}


const deleteComment = (eventid,commentid) => {
    const url = '/event/'+ eventid + '/delete/' + commentid;
    sendAjaxRequest('post', url, { 'eventid': eventid, 'commentid' : commentid }, deleteCommentHandler(commentid));
}

function deleteCommentHandler(commentid) {
    const comment = document.querySelectorAll('#comment' + commentid)
    for(var i = 0; i < comment.length; i++) {
        comment[i].remove()        
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
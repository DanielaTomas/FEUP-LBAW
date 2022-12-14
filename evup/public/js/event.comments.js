const deleteComment = (id,commentid) => {
    const url = '/event/'+ id + '/delete/' + commentid;
    sendAjaxRequest('post', url, { 'eventid': id, 'commentid' : commentid }, deleteCommentHandler(commentid));
}

function deleteCommentHandler(commentid) {
    const comment = document.getElementById("comment" + commentid)
    comment.remove()
    createAlert('success', 'You have removed this comment successfully.')
}

const like = (id,commentid) => {
    const url = '/event/'+ id + '/like/' + commentid;
    sendAjaxRequest('post', url, { 'eventid': id, 'commentid' : commentid }, likeHandler(commentid));
}

function likeHandler(commentid) {
    let count = document.querySelector("#count");
    count.textContent++;
}   
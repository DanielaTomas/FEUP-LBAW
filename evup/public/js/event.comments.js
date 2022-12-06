const deleteComment = (id,commentid) => {
    sendAjaxRequest('post', '/event/${id}/delete/${commentid}', { 'eventid': id, 'commentid' : commentid }, deleteCommentHandler(commentid));
}

function deleteCommentHandler(commentid) {
    const comment = document.getElementById("comment" + commentid)
    comment.remove()
}
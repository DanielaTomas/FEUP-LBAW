const deleteComment = (id,commentid) => {
    const url = '/event/'+ id + '/delete/' + commentid;
    sendAjaxRequest('post', url, { 'eventid': id, 'commentid' : commentid }, deleteCommentHandler(commentid));
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
        createAlert('error','You cannot vote on your own comments or you already voted on this comment');
        alert('You cannot vote on your own comments or you already voted on this comment');
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
        createAlert('error','You cannot vote on your own comments or you already voted on this comment');
        alert('You cannot vote on your own comments or you already voted on this comment');
    }
}

function dislikeHandler(commentid) {
    let count = document.querySelector("#dislikeCount-" + commentid);
    count.innerHTML++;
    hasVoted[commentid] = true;
}   
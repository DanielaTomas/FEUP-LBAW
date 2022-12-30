const createNewComment = (eventid) => {
    const url = '/event/' + eventid + '/createComment/';
    const body = select('#commentTextArea').value;
    const file = select('#commentFileInput').files[0];
    const formData = new FormData();
    if (!body) return;
    else if (file){
        formData.append('commentfile[]', file);
    }

    let xhr = new XMLHttpRequest();

    xhr.open("POST", url, true);
    xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').content);
    xhr.addEventListener('load', newCommentHandler(false));
    formData.append('commentcontent[]',body);
    formData.append('eventid[]',eventid);
    console.log(xhr)
    xhr.send(formData);
}

const createNewReply = (parent, eventid, parentid) => {
    const url = '/event/' + eventid + '/createComment/' + parentid;
    const body = select(`#replyTextArea-${parentid}`).value;
    if (!body) return;

    sendAjaxRequest('post', url, { 'commentcontent': body, 'eventid': eventid, 'parentid': parentid }, newCommentHandler(true, parent, 'afterend', `#replyTextArea-${parentid}`));
}

const editComment = (commentId, editBox) => {
    const body = select(`#edit_textarea_${commentId}`).value;
    if (!body) return;

    sendAjaxRequest('PUT', `/comment/${commentId}`, { body }, editCommentHandler(commentId, editBox));
}

const newCommentHandler = (reply, parent = select('#comments'), position = 'afterbegin', textarea = '#commentTextArea') => function () {
    const json = JSON.parse(this.responseText);

    if (this.status != 200) {
        createAlert('error', json.errors);
    }
    if (reply) {
        createAlert('success', 'You have added a new reply successfully.');
    }
    else {
        createAlert('success', 'You have added a new comment successfully.');
    }

    parent.insertAdjacentHTML(position, json.html);
    select(textarea).value = '';

}


const deleteComment = (eventid, commentid) => {
    const url = '/event/' + eventid + '/delete/' + commentid;
    sendAjaxRequest('post', url, { 'eventid': eventid, 'commentid': commentid }, deleteCommentHandler(commentid));
}

function deleteCommentHandler(commentid) {
    const comment = document.querySelectorAll('#comment' + commentid)
    for (var i = 0; i < comment.length; i++) {
        comment[i].remove()
    }
    createAlert('success', 'You have removed this comment successfully.')
}

var hasVoted = new Array(500).fill(false);

const like = (id, commentid, voted) => {
    if (!voted && !hasVoted[commentid]) {
        const url = '/event/' + id + '/like/' + commentid + '/voted/' + voted;
        sendAjaxRequest('post', url, { 'eventid': id, 'commentid': commentid }, likeHandler(commentid));
    }
    else {
        createAlert('warning', 'You already voted on this comment');
        alert('You already voted on this comment');
    }
}

function likeHandler(commentid) {
    let count = document.querySelector("#likeCount-" + commentid);
    count.innerHTML++;
    hasVoted[commentid] = true;
}

const dislike = (id, commentid, voted) => {
    if (!voted && !hasVoted[commentid]) {
        const url = '/event/' + id + '/dislike/' + commentid + '/voted/' + voted;
        sendAjaxRequest('post', url, { 'eventid': id, 'commentid': commentid }, dislikeHandler(commentid));
    }
    else {
        createAlert('warning', 'You already voted on this comment');
        alert('You already voted on this comment');
    }
}

function dislikeHandler(commentid) {
    let count = document.querySelector("#dislikeCount-" + commentid);
    count.innerHTML++;
    hasVoted[commentid] = true;
}   
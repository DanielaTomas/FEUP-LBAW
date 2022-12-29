function deleteUser(id) {
    const url = '/admin/users/'+ id + '/delete';
    sendAjaxRequest('put', url, null, delHandler(id));
}

function banUser(id) {
    const url = '/admin/users/'+ id + '/ban';
    sendAjaxRequest('put', url, null, banHandler(true, id));
}

function unbanUser(id) {
    const url = '/admin/users/'+ id + '/unban';
    sendAjaxRequest('put', url, null, banHandler(false, id));
}

function appealUnbanUser(id, appealid) {
    const url = '/admin/users/'+ id + '/unban';
    sendAjaxRequest('put', url, { 'appealid': appealid }, appealUnbanHandler(id));
}

function delHandler(id) {
    if (this.status == 403) {
        window.location = '/login';
        return;
    }

    banbtn = select('#banBtn-' + id)
    if(banbtn)
        banbtn.remove()
    else {
        unbanbtn = select('#unbanBtn-' + id)
        unbanbtn.remove()
    }

    accstatus = select('#accstatus-' + id)
    accstatus.innerHTML = 'Disabled'
    accstatus.classList = 'bg-red-200 text-red-600 py-1 px-3 rounded-full text-xs'

    createAlert('success', "You have delete this user's account successfully.")
}

function banHandler(ban, id) {
    if (this.status == 403) {
        window.location = '/login';
        return;
    }

    /* Deal with errors */

    if (ban) {                              /* Changes BAN btn to UNBAN btn after a user has ben Banned */
        confirmBtn = select('#confirmbanBtn-' + id)
        confirmBtn.setAttribute('onclick','unbanUser(' + id + ')')
        confirmBtn.id = 'confirmunbanBtn-' + id

        confirmTxt = select('#confirmbanTxt-' + id)
        confirmTxt.id = 'confirmunbanTxt-' + id
        confirmTxt.innerHTML = 'Would you like to unban this user?'

        btn = select('#banBtn-' + id)
        btn.id = 'unbanBtn-' + id
        btn.innerHTML = 'Unban'
        btn.classList = 'block text-white bg-gray-700 hover:bg-gray-800 focus:ring-4 focus:outline-none focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-gray-600 dark:hover:bg-gray-700 dark:focus:ring-gray-800" type="button" data-modal-toggle="staticModal-' + id
        
        accstatus = select('#accstatus-' + id)
        accstatus.innerHTML = 'Blocked'
        accstatus.classList = 'bg-yellow-200 text-yellow-600 py-1 px-3 rounded-full text-xs'

        createAlert('success', 'You have banned this user successfully.')
    }

    else {                                  /* Changes UNBAN btg to BAN btn after a user has ben UNbanned */
        confirmBtn = select('#confirmunbanBtn-' + id)
        confirmBtn.setAttribute('onclick','banUser(' + id + ')')
        confirmBtn.id = 'confirmbanBtn-' + id

        confirmTxt = select('#confirmunbanTxt-' + id)
        confirmTxt.id = 'confirmbanTxt-' + id
        confirmTxt.innerHTML = 'Would you like to ban this user?'

        btn = select('#unbanBtn-' + id)
        btn.id = 'banBtn-' + id
        btn.innerHTML = 'Ban'
        btn.classList = 'block text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800" type="button" data-modal-toggle="staticModal-' + id

        accstatus = select('#accstatus-' + id)
        accstatus.innerHTML = 'Active'
        accstatus.classList = 'bg-green-200 text-green-600 py-1 px-3 rounded-full text-xs'

        createAlert('success', 'You have unbanned this user successfully.')
    }

}

function appealUnbanHandler(id) {
    if (this.status == 403) {
        window.location = '/login';
        return;
    }

    /* Deal with errors */
                                /* Changes UNBAN btg to BAN btn after a user has ben UNbanned */
    confirmBtn = select('#confirmunbanBtn-' + id)
    confirmBtn.setAttribute('onclick','banUser(' + id + ')')
    confirmBtn.id = 'confirmbanBtn-' + id

    confirmTxt = select('#confirmunbanTxt-' + id)
    confirmTxt.id = 'confirmbanTxt-' + id
    confirmTxt.innerHTML = 'Would you like to ban this user?'

    btn = select('#unbanBtn-' + id)
    btn.id = 'banBtn-' + id
    btn.innerHTML = 'Ban'
    btn.classList = 'block text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800" type="button" data-modal-toggle="staticModal-' + id

    accstatus = select('#accstatus-' + id)
    accstatus.innerHTML = 'Active'
    accstatus.classList = 'bg-green-200 text-green-600 py-1 px-3 rounded-full text-xs'


    appealUnbanBtn = select('#appealUnbanBtn-' + id)
    div = appealUnbanBtn.parentElement
    appealUnbanBtn.remove()
    div.insertAdjacentHTML('afterend','<div class="text-center font-bold mb-2 text-lg">Unban Appeal Accepted</div>')

    createAlert('success', 'You have unbanned this user successfully.')
    

}
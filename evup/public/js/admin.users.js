function banUser(id) {
    const url = '/admin/users/'+ id + '/ban';
    sendAjaxRequest('put', url, null, banHandler(true, id));
}

function unbanUser(id) {
    const url = '/admin/users/'+ id + '/unban';
    sendAjaxRequest('put', url, null, banHandler(false, id));
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
        
        accstatus = btn.parentElement.parentElement.parentElement.previousElementSibling.querySelector('span')
        accstatus.innerHTML = 'Blocked'
        accstatus.classList = 'bg-yellow-200 text-yellow-600 py-1 px-3 rounded-full text-xs'

        //createAlert('success', 'You have banned this user successfully.')
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

        accstatus = btn.parentElement.parentElement.parentElement.previousElementSibling.querySelector('span')
        accstatus.innerHTML = 'Active'
        accstatus.classList = 'bg-green-200 text-green-600 py-1 px-3 rounded-full text-xs'

        //createAlert('success', 'You have unbanned this user successfully.')
    }

}
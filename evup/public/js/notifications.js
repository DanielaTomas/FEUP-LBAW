fetchNotifications()

function notificationPanelHandler() {
    if (this.status == 403) {
        window.location = '/login';
        return;
    }
    if (this.status != 200) return;

    const notifications = JSON.parse(this.responseText)
    const area = select("#notificationPanel");
    area.innerHTML = notifications

    /* Added red circle on Bell icon if notifications aren't emptpy */
    if (!notifications.includes("You don't have any new notifications"))
    {
        button = select('#dropdownNotificationButton')
        div = document.createElement('div')
        bellicon = document.createElement('div')
        div.id = 'bellcircle'
        div.classList = 'flex relative'
        bellicon.classList = 'inline-flex relative -top-2 right-3 w-3 h-3 bg-red-500 rounded-full border-2 border-white dark:border-gray-900'
        button.innerHTML = '<svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"></path></svg>'
        div.append(bellicon)
        button.append(div)
    }
}

function notificationsCleanerHandler() {
    
    const area = select("#notificationPanel");
    while (area.firstChild) {
        area.removeChild(area.firstChild);
    }
    bellcircle = select('#bellcircle')
    if (bellcircle) {
        bellcircle.remove()
        createAlert('success', 'You have read all your notifications.')
    }
    else
        createAlert('warning', "You don't have any unread notifications.")

    div = document.createElement('div')
    div.classList = 'py-2 px-4 text-center text-gray-500'
    div.textContent = "You don't have any new notifications"
    area.append(div)
}

function notificationCleanerHandler(id) {
    const area = select("#notificationPanel");
    const notification = select("#notification-" + id);

    notification.remove();

    if (area.childElementCount == 0) {
        bellcircle = select('#bellcircle')
        bellcircle.remove()

        div = document.createElement('div')
        div.classList = 'py-2 px-4 text-center text-gray-500'
        div.textContent = "You don't have any new notifications"
        area.append(div)

        createAlert('success', 'You have read all your notifications.')
    }
}

function fetchNotifications() {
    sendAjaxRequest('get', '/api/notifications', null, notificationPanelHandler);
}

function readNotifications() {
    // Mark all notifications as read
    sendAjaxRequest('put', '/notifications', null, notificationsCleanerHandler);
}

function readNotification(id) {
    // Mark a single notification with notificationid = id as read
    const url = '/notifications/'+ id;
    sendAjaxRequest('put', url, null, notificationCleanerHandler(id));
}
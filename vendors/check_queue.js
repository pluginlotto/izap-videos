function checkQueue() {
    $('#videoQueue').load(load_url);
}
$(document).ready(function() {
    checkQueue();
    setInterval(checkQueue, 1000);
});

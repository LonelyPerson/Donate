$(document).ready(function() {
    if (getPage() != '')
        loadView(getPage());
    else
        loadView('login');
});
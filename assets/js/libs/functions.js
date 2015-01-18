function loadPage(page, controller, callback) {
    blockScreen();
    
    $('#jas-content').load('ajax.php?route=' + controller, function() {
        setPage(page);
        callback();
        
        // set active menu item
        $('.nav li').removeClass('active');
        $('.nav li#' + page).addClass('active');
        
        unblockScreen();
    });
}

function loadView(view) {
    $.getScript('assets/js/views/' + view + '.view.js');
}

function getPage() {
    var page = window.location.hash;
    page = page.replace('#', '');
    
    return page;
}

function getPageByKey(key) {
    if (getPage() == key) return true;
    
    return false;
}

function setPage(page) {
    window.location.hash = page;
}

function logout() {
    $.post('ajax.php', { logout: true }, function(response) {
        if (response.success)
            loadView('login');
    });
}

function selectCharacter(id, name) {
    $.post('ajax.php', { select_character: true, character_obj_id: id, character_name: name }, function(response) {
        if (response.success) {
            $('#selected-char').html(name);
        }
    });
}

function loadCaptcha(key) {
    Recaptcha.create(key,
        "captcha", {
            theme: "custom",
            callback: Recaptcha.focus_response_field
        }
    );
}

function formatMessage(message, type) {
    return '<div class="alert alert-' + type + '">' + message + '</div>';
}

function blockScreen() {
    $.blockUI({
        message: '<span style="font-size: 30px;"></span>',
        overlayCSS:  { 
            backgroundColor: '#fff', 
            opacity: 0.8, 
            cursor: 'pointer' 
        }, 
        css: { 
            padding:        0, 
            margin:         0, 
            width:          '30%', 
            top:            '40%', 
            left:           '35%', 
            textAlign:      'center', 
            color:          '#000', 
            border:         'none', 
            backgroundColor:'transparent', 
            cursor:         'pointer' 
        }, 
    });
}
function unblockScreen() {
    $.unblockUI();
}

function setLanguage(code) {
    blockScreen();
    
    $.post('ajax.php', { set_language: true, language: code }, function(response) {
        if (response.success) {
            window.location.reload();
        }
    });
}
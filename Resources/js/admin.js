bootbox = require('bootbox');
require('block-ui');

var LANG = {
    'wait': 'Please wait...'
};


$(function () {

    $('input').iCheck({
        checkboxClass: 'icheckbox_square-blue',
        radioClass: 'iradio_square-blue',
        increaseArea: '20%' // optional
    });

    // generic function for confirm dialog using bootbox
    $('[data-confirm="confirm"]').click(function (e) {
        e.preventDefault();
        var that = $(this)
        bootbox.confirm($(this).attr('data-title'), function (result) {
            if (result) {
                blockUi();
                window.location.href = that.attr('data-href');
            }
        });
    });

    // generic function to suppress form submit on return key
    $('.noenter').keydown(function(event){
        if (event.which == 13) {
            event.preventDefault();
            event.stopPropagation();
            return false;
        }
    });

    function blockUi()
    {
        $.blockUI({
            css: {
                border: 'none',
                padding: '15px',
                backgroundColor: '#000',
                '-webkit-border-radius': '10px',
                '-moz-border-radius': '10px',
                opacity: .5,
                color: '#fff'
            },
            baseZ: 9999,
            message: '<h1>' + LANG.wait + '</h1>'
        });
    }

    $('.blockui').on('click', function() {
        blockUi();
    });
    $('form:not(.noBlockUi)').on('submit', function() {
        if ($(this).attr('target') == '_blank') {
            return;
        }

        blockUi();
    });

});

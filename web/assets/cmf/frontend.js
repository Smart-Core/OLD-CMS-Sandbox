$(document).ready(function() {
    // Отрисовка тулбара
    if (typeof cms_front_controls === 'object') {
        // Отрисовать тулбар.
        if (!$.isEmptyObject(cms_front_controls.toolbar)) {
            renderToolbar();
        }
    }

    // Переключатель "просмотр" и "редактирование" на тулбаре.
    $('.navbar .btn[data-toggle="button"]').click(function() {
        if ($(this).attr('class-toggle') != undefined && !$(this).hasClass('disabled')) {
            if ($(this).attr('data-toggle') == 'button') {
                if ($(this).hasClass('active')) {
                    $(this).removeClass($(this).attr('class-toggle'));
                    $(this).text(cms_front_controls.toolbar.right.eip_toggle[0]);
                    $('.cmf-frontadmin-node').removeClass('cmf-frontadmin-node-mode-edit');
                    $('.cmf-frontadmin-node').unbind('mouseenter mouseleave dblclick');

                    $('.cmf-empty-node').remove();
                    $.removeCookie('cmf-frontadmin-mode', { path: '/' });
                } else {
                    $(this).addClass($(this).attr('class-toggle'));
                    $(this).text(cms_front_controls.toolbar.right.eip_toggle[1]);
                    $('.cmf-frontadmin-node').addClass('cmf-frontadmin-node-mode-edit');

                    // Включить отрисовку панелей управления нодами.
                    $('.cmf-frontadmin-node-mode-edit').hover(
                        function(){
                            var elem = this;

                            if (typeof cms_front_controls.node[$(elem).attr('id')] === 'object') {
                                var node = cms_front_controls.node[$(elem).attr('id')];

                                var node_buttons = '<div class="overlay btn-group">';

                                // сначала поиск действия по умолчанию.
                                $.each(node, function(index, value) {
                                    if (value.default == true) {
                                        if (value.overlay != undefined && value.overlay == true) {
                                            node_buttons += '<button OnClick="window.location=\'#overlay=' + value.uri + '\'" title="' +
                                                value.descr +
                                                '" class="btn btn-small popup-trigger">' +
                                                value.title + '</button>';
                                        } else {
                                            node_buttons += '<button OnClick="window.location=\'' + value.uri
                                                + '?redirect_to=' + window.location.pathname + window.location.search
                                                + '\'" title="' + value.descr
                                                + '" class="btn btn-small popup-trigger cmf-no-overlay">' + value.title + '</button>';
                                        }
                                    }
                                });

                                node_buttons += '<button data-toggle="dropdown" class="btn btn-small dropdown-toggle"><span class="caret"></span></button>';
                                node_buttons += '<ul class="dropdown-menu">';

                                // затем отрисовка пунктов меню.
                                $.each(node, function(index, value) {
                                    if (value.overlay != undefined && value.overlay == true) {
                                        node_buttons += '<li><a class="popup-trigger" title="' + value.descr + '" href="' + value.uri + '">' ;

                                    } else {
                                        node_buttons += '<li><a class="popup-trigger cmf-no-overlay" title="' + value.descr
                                            + '" href="' + value.uri + '?redirect_to=' + window.location.pathname + window.location.search + '">' ;
                                    }

                                    if (value.default == true) {
                                        node_buttons += '<strong>' + value.title + '</strong></a></li>';
                                    } else {
                                        node_buttons += value.title + '</a></li>';
                                    }
                                });

                                node_buttons += '</ul></div>';

                                $(elem).prepend(node_buttons);
                            }

                            // обработчик кликов по ссылкам.

                            /*
                            $('.btn-group .dropdown-menu a').click(function() {
                                if ($(this).hasClass('cmf-no-overlay')) {
                                    window.location = $(this).attr('href');
                                } else {
                                    window.location.hash = '#overlay=' + $(this).attr('href');
                                }
                                return false;
                            });
                            */
                        },
                        function(){
                            var elem = this;
                            $(elem).find('.overlay.btn-group').hide().remove();
                        }
                    );

                    // Добавление дабл клика.
                    //$('.cmf-frontadmin-node-mode-edit').dblclick(function() { $.cmfOverlay('open'); return false; });

                    // заглушки для пустых нод.
                    $('.cmf-frontadmin-node').each(function() {
                        if( $.trim($(this).text()) == "" ){
                            $(this).append('<div class="cmf-empty-node"></div>');
                        }
                    });

                    $.cookie('cmf-frontadmin-mode', 'edit', { path: '/' }); // @todo настройку path в корень сайта.
                }
            }
        }
    });

    if(typeof $.cookie('cmf-frontadmin-mode') === 'string' && $.cookie('cmf-frontadmin-mode').toString() === 'edit') {
        $('.navbar .btn[data-toggle="button"]').click();
    }

    var $Overlay = null;
    var $Screen  = null;
    var DefaultSettings = {};
    var initialized = false;
    var opened      = false;

    var Methods = {
        init : function() {
            $Overlay = $('<div id="cmf-overlay" />');
            $Screen  = $('<div id="cmf-overlay-screen" />');
            //$Overlay.click(function() { $.cmfOverlay('close'); });
            //$Screen.click(function() { $.cmfOverlay('close'); });
            initialized = true;
        },

        //open : function(Options) {
        open : function(uri) {
            // Настройки.
            Settings = $.extend({}, DefaultSettings);

            /*
            if(Options) {
                $.extend(Settings, Options);
            }
            */

            if(!initialized) {
                $.cmfOverlay('init');
            }

            $('body').addClass('body-overlayed');
            $('body').append($Screen);

            $.ajax({url: uri, cache: false})
                .done(function( html ) {
                    html = html.replace(/<\s*head[^>]*>[\s\S]*?<\/head>/mig, "");

                    $Overlay.empty().append( $('<div id="cmf-overlay-window" />').append(html) );

                    //$('body').append($Screen).append($Overlay);
                    $('#cmf-overlay-screen').html($Overlay);
                    $('#cmf-overlay-window').prepend('<div id="cmf-overlay-window-close"><i class="icon-remove" title="Закрыть"></i></div>');
                    $('#cmf-overlay-window-close').click(function() { $.cmfOverlay('close'); });

                    // клики по всем ссылкам, кроме вложеных в .nav-tabs и .nav-pills, перехватываются и запрашиваются аяксом.
                    $('#cmf-overlay-window a:not(.nav-tabs a, .nav-pills a, .cmf-cancel)').click(function() {
                        $.cmfOverlay('open', $(this).attr('href'));
                        return false;
                    });

                    /*
                    $('button').click(function() {
                        return false;
                    });
                    */

                    //$('form').submit(function(e) {
                    $('button[type=submit], input[type=submit]').click(function(e) {
                        // stop form from submitting normally
                        e.preventDefault();
                        // get some values from elements on the page:
                        var form = $(this).parents('form');
                        $.ajax({
                            type: form.attr('method'),
                            url: form.attr('action'),
                            data: form.serialize() + '&' + $(this).attr('name') + '=' + $(this).attr('value'),
                            cache: false
                        }).done(function(data) {
                            if (data.redirect != undefined) {
                                window.location = data.redirect;
                            }
                            if (data.overlay_redirect != undefined) {
                                window.location.hash = '#overlay=' + data.overlay_redirect ;
                            }
                        }).fail(function(response) {
                            alert('@TODO Сообщение об ошибке\n\n' + response.responseText);
                            /*
                            var data = $.parseJSON(response.responseText)
                            $.each(data, function(key, val){
                                alert(key + ' = ' + val);
                            }); */
                        });

                        return false;
                    });

                    window.location.hash = '#overlay=' + uri;
                    opened = true;

                    $('.cmf-cancel').click(function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        $.cmfOverlay('close');
                        return false;
                    });
                })
                .fail(function() {
                    alert('Resource: ' + uri + ' not found'); // @todo убрать...
                    $Screen.detach();
                    $Overlay.detach();

                    $('body').removeClass('body-overlayed');
                    window.location.hash = '';
                    //$.cmfOverlay('close');
                });

        },

        close : function() {
            if(!opened) {
                return;
            }

            $Screen.detach();
            $Overlay.detach();

            $('body').removeClass('body-overlayed');
            window.location.hash = '';
        }
    };

    $.cmfOverlay = function(method)	{
        if(!Methods[method]) {
            method = 'open';
        }

        return Methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
    };

    // закрытие оверлея по ескейпу.
    $(document).keyup(function(e) {
        if (e.keyCode == 27) { // esc
            $.cmfOverlay('close');
        }
    });

    // обработчик кликов по ссылкам.
    /*
    $('.navbar .dropdown-menu a').click(function() {
        // закрытие меню.
        $('[data-toggle="dropdown"]').parent().removeClass('open');
        if ($(this).attr('cmf-overlay').toString() === 'on') {
            window.location.hash = '#overlay=' + $(this).attr('href');
            return false;
        }
    });
    */

    // Bind an event to window.onhashchange that, when the hash changes, gets the
    // hash and adds the class "selected" to any matching nav link.
    // http://benalman.com/code/projects/jquery-hashchange/examples/hashchange/
    $(window).hashchange( function(){
        var hash = location.hash;
        if (hash.length > 0 && hash.slice(0, 9) == '#overlay=') {
            // Set the page title based on the hash.
            document.title = 'The hash is: ' + ( hash.replace( /^#overlay=/, '' ) || 'blank' );
            $.cmfOverlay('open', hash.slice(9));
        } else {
            $.cmfOverlay('close');
        }

        /*
         // Iterate over all nav links, setting the "selected" class as-appropriate.
         $('#nav a').each(function(){
         var that = $(this);
         that[ that.attr( 'href' ) === hash ? 'addClass' : 'removeClass' ]( 'selected' );
         });
         */
    });

    // Since the event is only triggered when the hash changes, we need to trigger
    // the event now, to handle the hash the page may have loaded with.
    $(window).hashchange();
});


function renderToolbar() {
    $('body')
        .css('padding-top', '40px')
        .prepend('<div class="navbar navbar-inverse navbar-fixed-top">' +
        '<div class="navbar-inner">' +
        '<div class="container">' +
        '<button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse"><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></button>' +
        '<a class="brand" href="' + basePath +'" title="На главную">Smart Core CMS</a>' + // <i class="icon-home icon-white"></i>
        '<div class="nav-collapse collapse">' +
        '<ul class="nav"></ul>' +
        '<div class="pull-right">' +
        '<ul class="nav pull-right"></ul>' +
        '</div></div></div></div></div>')
    ;

    var overalay = '';
    // Элементы справа
    if (typeof cms_front_controls.toolbar.right === 'object') {
        $.each(cms_front_controls.toolbar.right, function(index, value) {
            if (index === 'eip_toggle') {
                $('body > div.navbar > div.navbar-inner > div.container > div.nav-collapse > div.pull-right')
                    .prepend('<button type="button" class="btn btn-primary span2" data-toggle="button" class-toggle="btn-danger">Просмотр</button>');
            } else {
                var item = '<li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-' + value.icon + ' icon-white"></i>&nbsp;' + value.title + '<b class="caret"></b></a>';

                // есть итемы.
                if (typeof value.items === 'object') {
                    item += '<ul class="dropdown-menu">';
                    $.each(value.items, function(index2, value2) {
                        if (value2 === 'diviver') {
                            item += '<li class="divider"></li>';
                        } else {
                            if (typeof value2.overalay === 'boolean' && value2.overalay === false) {
                                overalay = 'off';
                            } else {
                                overalay = 'on';
                            }
                            item += '<li><a href="' + value2.uri + '" class="popup-trigger" cmf-overlay="' + overalay + '"><i class="icon-' + value2.icon + '"></i>&nbsp;' + value2.title +'</a></li>';
                        }
                    });
                    item += '</ul>';
                }

                $('body > div.navbar > div.navbar-inner > div.container > div.nav-collapse > div.pull-right > ul.nav').prepend(item + '</li>');
            }
        });
    }

    // Элементы слева
    // отличается только cms_front_controls.toolbar.left и отсутствием > div.pull-right
    if (typeof cms_front_controls.toolbar.left === 'object') {
        $.each(cms_front_controls.toolbar.left, function(index, value) {
            if (index === 'eip_toggle') {
                $('body > div.navbar > div.navbar-inner > div.container > div.nav-collapse')
                    .append('<button type="button" class="btn btn-primary span2" data-toggle="button" class-toggle="btn-danger">Просмотр</button>');
            } else {

                var item = '<li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown" title="' +
                    value.descr + '"><i class="icon-' + value.icon + ' icon-white"></i>&nbsp;' + value.title + '<b class="caret"></b></a>';

                // есть итемы.
                if (typeof value.items === 'object') {
                    item += '<ul class="dropdown-menu">';
                    $.each(value.items, function(index2, value2) {
                        if (value2 === 'diviver') {
                            item += '<li class="divider"></li>';
                        } else {
                            if (typeof value2.overalay === 'boolean' && value2.overalay === false) {
                                overalay = 'off';
                            } else {
                                overalay = 'on';
                            }

                            item += '<li><a href="' + value2.uri + '?redirect_to=front'
                                + '" class="popup-trigger" cmf-overlay="' + overalay + '"><i class="icon-'
                                + value2.icon + '"></i>&nbsp;' + value2.title +'</a></li>';
                        }
                    });
                    item += '</ul>';
                }

                $('body > div.navbar > div.navbar-inner > div.container > div.nav-collapse > ul.nav').append(item + '</li>');
            }
        });
    }
}

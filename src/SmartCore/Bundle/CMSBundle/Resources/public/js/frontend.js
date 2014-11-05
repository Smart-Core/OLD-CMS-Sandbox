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

                            if (typeof cms_front_controls.nodes[$(elem).attr('id')] === 'object') {
                                var node = cms_front_controls.nodes[$(elem).attr('id')];

                                var node_buttons = '<div class="cmf-frontadmin-node-buttons btn-group">';

                                // сначала поиск действия по умолчанию.
                                $.each(node, function(index, value) {
                                    if (value.default == true) {
                                        node_buttons += '<button OnClick="window.location=\'' + value.uri
                                            + '?redirect_to=' + window.location.pathname + window.location.search
                                            + '\'" title="' + value.descr
                                            + '" class="btn btn-small popup-trigger">' + value.title + '</button>';
                                    }
                                });

                                node_buttons += '<button data-toggle="dropdown" class="btn btn-small dropdown-toggle"><span class="caret"></span></button>';
                                node_buttons += '<ul class="dropdown-menu">';

                                // затем отрисовка пунктов меню.
                                $.each(node, function(index, value) {
                                    node_buttons += '<li><a class="popup-trigger" title="' + value.descr
                                        + '" href="' + value.uri + '?redirect_to=' + window.location.pathname + window.location.search + '">' ;

                                    if (value.default == true) {
                                        node_buttons += '<strong>' + value.title + '</strong></a></li>';
                                    } else {
                                        node_buttons += value.title + '</a></li>';
                                    }
                                });

                                node_buttons += '</ul></div>';

                                $(elem).prepend(node_buttons);
                            }
                        },
                        function(){
                            var elem = this;
                            $(elem).find('.cmf-frontadmin-node-buttons.btn-group').hide().remove();
                        }
                    );

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
});

function renderToolbar() {
    $('body')
        .css('padding-top', '40px')
        .prepend('<div class="navbar navbar-inverse navbar-fixed-top">' +
        '<div class="navbar-inner">' +
        '<div class="container">' +
        '<button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse"><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></button>' +
        '<a class="brand" href="' + basePath +'" title="На главную"> <i class="icon-home icon-white"></i></a>' + // @todo бренд Smart Core CMS
        '<div class="nav-collapse collapse">' +
        '<ul class="nav"></ul>' +
        '<div class="pull-right">' +
        '<ul class="nav pull-right"></ul>' +
        '</div></div></div></div></div>')
    ;

    // Элементы справа
    if (typeof cms_front_controls.toolbar.right === 'object') {
        $.each(cms_front_controls.toolbar.right, function(index, value) {
            if (index === 'eip_toggle') {
                $('body > div.navbar > div.navbar-inner > div.container > div.nav-collapse > div.pull-right')
                    .prepend('<button type="button" class="btn btn-primary span2" data-toggle="button" class-toggle="btn-danger">' + value[0] + '</button>');
            } else {
                var item = '<li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-' + value.icon + ' icon-white"></i>&nbsp;' + value.title + '<b class="caret"></b></a>';

                // есть итемы.
                if (typeof value.items === 'object') {
                    item += '<ul class="dropdown-menu">';
                    $.each(value.items, function(index2, value2) {
                        if (value2 === 'diviver') {
                            item += '<li class="divider"></li>';
                        } else {
                            item += '<li><a href="' + value2.uri + '" class="popup-trigger"><i class="icon-' + value2.icon + '"></i>&nbsp;' + value2.title +'</a></li>';
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
                            item += '<li><a href="' + value2.uri + '?redirect_to=front'
                                + '" class="popup-trigger"><i class="icon-'
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

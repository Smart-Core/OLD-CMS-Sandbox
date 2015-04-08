$(document).ready(function() {
    twitterBootstrapVersion = cms_front_controls.twitterBootstrapVersion;

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
                    $('.cms-frontadmin-node').removeClass('cms-frontadmin-node-mode-edit');
                    $('.cms-frontadmin-node').unbind('mouseenter mouseleave dblclick');

                    $('.cms-empty-node').remove();
                    $.removeCookie('cms-frontadmin-mode', { path: '/' });
                } else {
                    $(this).addClass($(this).attr('class-toggle'));
                    $(this).text(cms_front_controls.toolbar.right.eip_toggle[1]);
                    $('.cms-frontadmin-node').addClass('cms-frontadmin-node-mode-edit');

                    // Включить отрисовку панелей управления нодами.
                    $('.cms-frontadmin-node-mode-edit').hover(
                        function(){
                            var elem = this;

                            if (typeof cms_front_controls.nodes[$(elem).attr('id')] === 'object') {
                                var node = cms_front_controls.nodes[$(elem).attr('id')];

                                var node_buttons = '<div class="cms-frontadmin-node-buttons btn-group">';

                                // сначала поиск действия по умолчанию.
                                $.each(node, function(index, value) {
                                    if (value.descr != undefined) {
                                        var button_title = value.descr;
                                    } else {
                                        var button_title = '';
                                    }

                                    if (value.default == true) {
                                        node_buttons += '<button OnClick="window.location=\'' + value.uri
                                            + '?redirect_to=' + window.location.pathname + window.location.search
                                            + '\'" title="' + button_title
                                            + '" class="btn btn-mini btn-xs popup-trigger">' + value.title + '</button>';
                                    }
                                });

                                node_buttons += '<button data-toggle="dropdown" class="btn btn-mini btn-xs dropdown-toggle"><span class="caret"></span></button>';
                                node_buttons += '<ul class="dropdown-menu">';

                                // затем отрисовка пунктов меню.
                                $.each(node, function(index, value) {
                                    if (value.descr != undefined) {
                                        var item_title = value.descr;
                                    } else {
                                        var item_title = '';
                                    }

                                    node_buttons += '<li><a class="popup-trigger" title="' + item_title
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
                            $(elem).find('.cms-frontadmin-node-buttons.btn-group').hide().remove();
                        }
                    );

                    // заглушки для пустых нод.
                    $('.cms-frontadmin-node').each(function() {
                        if( $.trim($(this).text()) == "" ){
                            $(this).append('<div class="cms-empty-node"></div>');
                        }
                    });

                    $.cookie('cms-frontadmin-mode', 'edit', { path: '/' }); // @todo настройку path в корень сайта.
                }
            }
        }
    });

    if(typeof $.cookie('cms-frontadmin-mode') === 'string' && $.cookie('cms-frontadmin-mode').toString() === 'edit') {
        $('.navbar .btn[data-toggle="button"]').click();
    }
});

function renderToolbar() {
    if (twitterBootstrapVersion == 2) {
        $('body') // Bootstrap 2
            .css('padding-top', '40px')
            .prepend('<div class="navbar navbar-inverse navbar-fixed-top">' +
                '<div class="navbar-inner">' +
                '<div class="container cms-toolbar">' +
                '<button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse"><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></button>' +
                '<a class="brand" href="' + basePath +'" title="На главную сайта"> <i class="icon-home icon-white"></i></a>' + // @todo бренд Smart Core CMS
                '<div class="nav-collapse collapse">' +
                '<ul class="nav"></ul>' +
                '<div class="pull-right">' +
                '<ul class="nav pull-right"></ul>' +
                '</div></div></div></div></div>')
        ;
    } else {
        $('body') // Bootstrap 3
            .css('padding-top', '30px')
            .prepend('<nav class="navbar navbar-inverse navbar-fixed-top">' +
                '<div class="container cms-toolbar">' +
                '<div class="navbar-header"><button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1"><span class="sr-only">Toggle navigation</span><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></button>' +
                '<a class="navbar-brand" href="' + basePath +'" title="На главную сайта"> <i class="glyphicon glyphicon-home glyphicon-white"></i></a>' + // @todo бренд Smart Core CMS
                '</div>' +
                '<div class="navbar-collapse collapse" id="bs-example-navbar-collapse-1">' +
                '<ul class="nav navbar-nav cms-navbar-left"></ul>' +
                '<div class="pull-right">' +
                '<ul class="nav navbar-nav navbar-right"></ul>' +
                '</div></div></div></nav>')
        ;
    }

    // Элементы справа
    if (typeof cms_front_controls.toolbar.right === 'object') {
        $.each(cms_front_controls.toolbar.right, function(index, value) {
            if (index === 'eip_toggle') {
                if (twitterBootstrapVersion == 2) {
                    var div_pull_right = $('body > div.navbar > div.navbar-inner > div.container > div.nav-collapse > div.pull-right');
                } else {
                    var div_pull_right = $('body > nav.navbar > div.container > div.navbar-collapse > div.pull-right');
                }

                div_pull_right.prepend('<button type="button" class="btn btn-primary btn-sm span2" data-toggle="button" class-toggle="btn-danger">' + value[0] + '</button>');
            } else {
                var item = '<li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="glyphicon glyphicon-' + value.icon + ' icon-' + value.icon + ' icon-white"></i>&nbsp;' + value.title + '<b class="caret"></b></a>';

                // есть итемы. btn-inverse
                if (typeof value.items === 'object') {
                    item += '<ul class="dropdown-menu">';
                    $.each(value.items, function(index2, value2) {
                        if (value2 === 'diviver') {
                            item += '<li class="divider"></li>';
                        } else {
                            item += '<li><a href="' + value2.uri + '" class="popup-trigger"><i class="glyphicon glyphicon-' + value2.icon + ' icon-' + value2.icon + '"></i>&nbsp;' + value2.title +'</a></li>';
                        }
                    });
                    item += '</ul>';
                }

                if (twitterBootstrapVersion == 2) {
                    $('body > div.navbar > div.navbar-inner > div.container > div.nav-collapse > div.pull-right > ul.nav').prepend(item + '</li>');
                } else {
                    $('body > nav.navbar > div.container > div.navbar-collapse > div.pull-right > ul.navbar-right').prepend(item + '</li>');
                }
            }
        });
    }

    // Элементы слева
    // отличается только cms_front_controls.toolbar.left и отсутствием > div.pull-right
    if (typeof cms_front_controls.toolbar.left === 'object') {
        $.each(cms_front_controls.toolbar.left, function(index, value) {
            if (index === 'eip_toggle') {
                if (twitterBootstrapVersion == 2) {
                    var div_pull_left = $('body > div.navbar > div.navbar-inner > div.container > div.nav-collapse');
                } else {
                    var div_pull_left = $('body > nav.navbar > div.container > div.navbar-collapse');
                }

                div_pull_left.append('<button type="button" class="btn btn-inverse span2" data-toggle="button" class-toggle="btn-danger">' + value[0] + '</button>');
            } else {
                // есть итемы.
                if (typeof value.items === 'object') {
                    var item = '<li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown" title="' +
                        value.descr + '"><i class="glyphicon glyphicon-' + value.icon + ' icon-' + value.icon + ' icon-white glyphicon -white"></i>&nbsp;' + value.title + '<b class="caret"></b></a>';

                    item += '<ul class="dropdown-menu">';

                    $.each(value.items, function(index2, value2) {
                        if (value2 === 'diviver') {
                            item += '<li class="divider"></li>';
                        } else {
                            item += '<li><a href="' + value2.uri + '?redirect_to=front'
                                + '" class="popup-trigger"><i class="glyphicon glyphicon-' + value2.icon + ' icon-'
                                + value2.icon + '"></i>&nbsp;' + value2.title +'</a></li>';
                        }
                    });
                    item += '</ul>';
                } else {
                    var item = '<li><a class="btn-inverse" href="' + value.uri + '?redirect_to=' + window.location.pathname + window.location.search
                        + '" title="' + value.descr + '">' + value.title + '</a>';
                }

                if (twitterBootstrapVersion == 2) {
                    $('body > div.navbar > div.navbar-inner > div.container > div.nav-collapse > ul.nav').append(item + '</li>');
                } else {
                    $('body > nav.navbar > div.container > div.navbar-collapse > ul.cms-navbar-left').append(item + '</li>');
                }
            }
        });
    }

    // Уведомления
    if (typeof cms_front_controls.toolbar.notifications === 'object') {
        var count = Object.keys(cms_front_controls.toolbar.notifications).length;

        if (count > 0) {
            var item = '<li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown" title="Уведомления"><i class="glyphicon glyphicon-bullhorn icon-bullhorn icon-white"></i> <span class="label label-danger label-important">' + count + '</span></a>';

            item += '<ul class="dropdown-menu">';
            $.each(cms_front_controls.toolbar.notifications, function(index, value) {
                if (typeof value === 'object') {
                    $.each(value, function(index2, value2) {
                        console.log(value2);
                        item += '<li><a href="' + value2.url + '">' + value2.title;
                        if (value2.count > 0) {
                            item += ' <span class="label label-danger label-important">' + value2.count + '</span>';
                        }
                        item += '</a></li>';
                    });
                }
            });
            item += '</ul>';

            if (twitterBootstrapVersion == 2) {
                $('body > div.navbar > div.navbar-inner > div.container > div.nav-collapse > div.pull-right > ul.nav').prepend(item + '</li>');
            } else {
                $('body > nav.navbar > div.container > div.navbar-collapse > div.pull-right > ul.navbar-right').prepend(item + '</li>');
            }
        }
    }
}

$(document).ready(function () {

    $("main#spapp > section").height($(document).height() - 60);

    var app = $.spapp({pageNotFound: 'error_404'}); // initialize
    // console.log("🚀 ~ $ ~ app:", app);


    // Funkcija za učitavanje reusable dijelova
    function loadCommonParts(data) {

        var view = $("#" + data.view);
        var viewHtml = view.html();


        var matches = viewHtml.match(/{{(.*?)}}/g);
        // console.log("🚀 ~ loadCommonParts ~ matches:", matches);

        if (matches) {
            matches.forEach(function (match) {

                var fileName = match.replace('{{', '').replace('}}', '') + '.html';
                $.get(app.getTemplateDir() + 'partials/' + fileName, function (fileData) {

                    viewHtml = viewHtml.replace(match, fileData);
                    view.html(viewHtml);


                })
                    .fail(function (jqXHR, textStatus, errorThrown) {
                        console.error("Error loading file:", fileName, textStatus, errorThrown);
                    })
                ;
            });
        }

    }

    function loadCss(view) {
        console.log("🚀 ~ loadCss ~ view:", view)

        var link = document.createElement('link');
        link.rel = 'stylesheet';
        link.type = 'text/css';
        link.href = 'assets/css/' + view + '.css';
        document.getElementsByTagName('head')[0].appendChild(link);
    }


    // Extend the app.route method to include loadTemplate
    var originalRoute = app.route;
    app.route = function (options) {

        var originalOnCreate = options.onCreate;
        var originalOnReady = options.onReady;


        options.onCreate = function () {
            console.log("🚀 ~ $ ~ originalOnCreate:", originalOnCreate)
            console.log("🚀 ~ $ ~ $(this)[0]:", $(this)[0])

            loadCommonParts($(this)[0]);
            // laodTemplate($(this));


            if (originalOnCreate) {
                originalOnCreate.call(this);
            }
        };

        options.onReady = function () {
            $('#onCreate').html('First run !!!!');
            if (originalOnReady) {
                originalOnReady.call(this);
            }
        };


        originalRoute.call(app, options);
    };

    // define routes
    app.route({
        view: 'view_1',
        onCreate: function () {
            $("#view_1").append($.now() + ': Written on create<br/>');
            loadCss('view_1');
            loadCss('header');
            loadCss('header');
        },
        onReady: function () {

            $("#view_1").append("<div id='onCreate'></div>");
            $("#onCreate").append($.now() + ': Written when ready<br/>');
        }
    });
    app.route({view: 'view_2', load: 'view_2.html'});
    app.route({
        view: 'view_3',
        onCreate: function () {
            $("#view_3").append("I'm the third view");
        }
    });

    app.route({
        view: 'index',
        onCreate: function () {
            $("#index").append("INDEX CALLED");
            loadCss('core');
            loadCss('front-page/swiper/swiper');
            loadCss('front-page/front-page');
            loadCss('front-page/front-page-landing');
            // loadCss('demo');

        }
    });

    app.route({
        view: 'service',

        onCreate: function () {
            $("#index").append("SERVICE CALLED");
            // loadCss('core');
            // loadCss('front-page/swiper/swiper');
            // loadCss('front-page/front-page');
            // loadCss('front-page/front-page-landing');
            // loadCss('demo');

        },
        onReady: function () {
            $.getScript("assets/js/service_detail.js", function() {
                console.log("JavaScript for exampleView loaded.");
            });
        }
    });
    app.route({
        view: 'dashboard',

        onCreate: function () {
            $("#index").append("dashboard CALLED");
            loadCss('core');
            // loadCss('front-page/swiper/swiper');
            // loadCss('front-page/front-page');
            // loadCss('front-page/front-page-landing');
            // loadCss('demo');

        },
        onReady: function () {

        }
    });

    app.route({
        view: 'manage',

        onCreate: function () {
            $("#index").append("MANAGE CALLED");
            loadCss('core');
            // loadCss('front-page/swiper/swiper');
            // loadCss('front-page/front-page');
            // loadCss('front-page/front-page-landing');
            // loadCss('demo');

        },
        onReady: function () {

        }
    });

    app.route({
        view: 'profile',

        onCreate: function () {
            $("#index").append("MANAGE CALLED");
            loadCss('core');
            // loadCss('front-page/swiper/swiper');
            // loadCss('front-page/front-page');
            // loadCss('front-page/front-page-landing');
            // loadCss('demo');

        },
        onReady: function () {

        }
    });


    // run app
    app.run();

});


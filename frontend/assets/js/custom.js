$(document).ready(function() {

  $("main#spapp > section").height($(document).height() - 60);

  var app = $.spapp({pageNotFound : 'error_404'}); // initialize

  // Funkcija za učitavanje reusable dijelova
  function loadCommonParts(data) {

    var view = $("#" + data.view);
    var viewHtml = view.html();


    var matches = viewHtml.match(/{{(.*?)}}/g);
    // console.log("🚀 ~ loadCommonParts ~ matches:", matches);

    if (matches) {
      matches.forEach(function(match) {
        
        var fileName = match.replace('{{', '').replace('}}', '') + '.html';
        $.get('partials/' + fileName, function(fileData) {

            viewHtml = viewHtml.replace(match, fileData);
            view.html(viewHtml);
 

        })
        .fail(function(jqXHR,  textStatus, errorThrown) {
          console.error("Error loading file:", fileName, textStatus, errorThrown);
        })
        ;
      });
    }
  
}


 
  // Extend the app.route method to include loadTemplate
  var originalRoute = app.route;
  app.route = function(options) {
    
    var originalOnCreate = options.onCreate;
    var originalOnReady = options.onReady;



    options.onCreate = function() {
    console.log("🚀 ~ $ ~ originalOnCreate:", originalOnCreate)
      
    loadCommonParts($(this)[0]);
    // laodTemplate($(this));
    

      if (originalOnCreate) {
        originalOnCreate.call(this);
      }
    };

    options.onReady = function() {
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
    onCreate: function() {
      $("#view_1").append($.now()+': Written on create<br/>'); 
       },
    onReady: function() {

      $("#view_1").append("<div id='onCreate'></div>");
      $("#onCreate").append($.now()+': Written when ready<br/>'); 
    }
  });
  app.route({view: 'view_2', load: 'view_2.html' });
  app.route({
    view: 'view_3', 
    onCreate: function() { $("#view_3").append("I'm the third view"); }
  });


  // run app
  app.run();

});


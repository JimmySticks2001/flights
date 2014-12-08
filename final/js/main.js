
$(document).ready(function() {
  $.get("maps.html", function(data) { 
    $("#googleMap").append(data); 
    var page = window.location.href.slice(window.location.href.lastIndexOf("/")+1);
    $("a[href*='" + page + "']").parent().addClass("active");
  });
});

<!doctype html>
<html class="no-js" lang="en">
    <head>
        <title>Flight finder</title>
        <meta name="author" content="Tim, Sunny, Pedro, Gilnei" />
        <meta name="description" content="Flights!" />
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <link rel="stylesheet" href="css/foundation.css" />
        <script src="js/vendor/modernizr.js"></script>

        <style type="text/css">
            #result
            {
                position: absolute;
                width: 1000px;
                padding: 0px;
                display: none;
                margin-top: -1px;
                border-top: 0px;
                overflow: hidden;
                border: 1px #CCC solid;
                background-color: white;
            }
            .show
            {
                border-bottom: 1px #eee solid;
                font-size: 15px; 
                height: 25px;
            }
            .show:hover
            {
                background: #4c66a4;
                color: #FFF;
                cursor: pointer;
            }
        </style>
    </head>
    <body>

        <h1>Flight Planner</h1>
        <h4>Origin</h4>

        <input type="text" class="search" id="originCountry" placeholder="Country"/> 
        <div id="result"></div>
        <br/>
        <input type="text" class="search" id="searchid" placeholder="City"/> 
        <div id="result"></div>


        <br/>
        <h4>Destination</h4>






        
        <script src="js/vendor/jquery.js"></script>
        <script src="js/foundation.min.js"></script>
        <script>
            $(document).foundation();
        </script>
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
        <script type="text/javascript">
            $(function(){
                $(".search").keyup(function() 
                {
                    var searchid = $(this).val();
                    var dataString = "search="+searchid;
                    if(searchid != '')
                    {
                        $.ajax({
                            type: "POST",
                            url: "search.php",
                            data: dataString,
                            cache: false,
                            success: function(html)
                            {
                                $("#result").html(html).show();
                            }
                        });
                    }return false;    
                });

                
                jQuery("#result").live("click",function(e){ 
                    var $clicked = $(e.target);
                    var $name = $clicked.find(".name").html();
                    var decoded = $("<div/>").html($name).text();
                    $("#originCountry").val(decoded);
                });

                jQuery(document).live("click", function(e) { 
                    var $clicked = $(e.target);
                    if (! $clicked.hasClass("search")){
                    jQuery("#result").fadeOut(); 
                    }
                });
                
                $("#originCountry").click(function(){
                    jQuery("#result").fadeIn();
                });
                
            });
        </script>
    </body>
</html>
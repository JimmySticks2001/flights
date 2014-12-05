// On Search Submit and Get Results
function search() {
    var query_value = $('input#search').val();
    $('b#search-string').html(query_value);
    if(query_value !== ''){
        $.ajax({
            type: "POST",
            url: "search.php",
            data: { query: query_value },
            cache: false,
            success: function(html){
                $("ul#results").html(html);
            }
        });
    }return false;
}
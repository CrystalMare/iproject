function menufixer() {
    console.log("ya");
    $('.menu-button-active').removeClass('menu-button-active');
}

$(document).ready(function( ) {
    $(".cat").hover(
        //Hovering button

        function() {
            if ($(this).attr("id") != "hover")
                updateHover($(this).attr("id").substring(1));

            $("#hover").css("display", "inline");
            if ($(this).attr("id") != "hover") {
                $('.menu-button-active').removeClass('menu-button-active');
            }
            var menubutton = $(this);

            var linkinbutton = $('#' + menubutton.attr('id') + ' a');
            linkinbutton.addClass('menu-button-active');

            //console.log(menubutton);
            //color: #1aa9f1;
            //background-color: #fff;

        },
        //No longer hovering button
        function() {
            $("#hover").css("display", "none")
        }
    );
    setInterval(menufixer, 500);
});



function updateHover(menuid) {
    var url = "inc/categories.php?cat=" + menuid;
    $.ajax({
        url:url, success: function(result) {

            var html = "";
            for (var value in result) {
                html += "<li><a href='?page=overzicht&category=" + value +  "'>" + result[value]['rubrieknaam'] + "</a></li>";
                console.log(result[value]['rubrieknaam']);
            }
            $("#lijst").html(html);
        },
        complete: function() {

        }
    })
}
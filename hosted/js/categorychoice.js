$(document).ready(function() {
    $("#category1").change(function () {
        console.log("called");
        var category = $(this).val();
        //http://localhost:8080/inc/categories.php?cat=160
        var ajaxurl = "inc/categories.php?cat=" + category;
        console.log(ajaxurl);
        $.ajax({
            url: ajaxurl,
            success: function(result) {
                var subs = $('#category1sub1')
                subs.empty();
                for (var cat in result) {
                    console.log(result[cat]);
                    subs.append($("<option></option>")
                        .attr("value", result[cat]['rubrieknummer'])
                        .text(result[cat]['rubrieknaam']));
                }

                $("#category1sub4").empty();
                $("#category1sub3").empty();
                $("#category1sub2").empty();
                subs.trigger("change");

            }
        });

    });

    $("#category1sub1").change(function () {
        console.log("called");
        var category = $(this).val();
        //http://localhost:8080/inc/categories.php?cat=160
        var ajaxurl = "inc/categories.php?cat=" + category;
        console.log(ajaxurl);
        $.ajax({
            url: ajaxurl,
            success: function(result) {
                var subs = $('#category1sub2')
                subs.empty();
                for (var cat in result) {
                    console.log(result[cat]);
                    subs.append($("<option></option>")
                        .attr("value", result[cat]['rubrieknummer'])
                        .text(result[cat]['rubrieknaam']));
                }

                $("#category1sub4").empty();
                $("#category1sub3").empty();
                subs.trigger("change");

            }
        });

    });

    $("#category1sub2").change(function () {
        console.log("called");
        var category = $(this).val();
        //http://localhost:8080/inc/categories.php?cat=160
        var ajaxurl = "inc/categories.php?cat=" + category;
        console.log(ajaxurl);
        $.ajax({
            url: ajaxurl,
            success: function(result) {
                var subs = $('#category1sub3')
                subs.empty();
                for (var cat in result) {
                    console.log(result[cat]);
                    subs.append($("<option></option>")
                        .attr("value", result[cat]['rubrieknummer'])
                        .text(result[cat]['rubrieknaam']));
                }

                $("#category1sub4").empty();
                subs.trigger("change");
            }
        });

    });

    $("#category1sub3").change(function () {
        console.log("called");
        var category = $(this).val();
        //http://localhost:8080/inc/categories.php?cat=160
        var ajaxurl = "inc/categories.php?cat=" + category;
        console.log(ajaxurl);
        $.ajax({
            url: ajaxurl,
            success: function(result) {
                var subs = $('#category1sub4')
                subs.empty();
                for (var cat in result) {
                    console.log(result[cat]);
                    subs.append($("<option></option>")
                        .attr("value", result[cat]['rubrieknummer'])
                        .text(result[cat]['rubrieknaam']));
                }
                subs.trigger("change");
            }
        });
    });

    $("#category2").change(function () {
        console.log("called");
        var category = $(this).val();
        //http://localhost:8080/inc/categories.php?cat=160
        var ajaxurl = "inc/categories.php?cat=" + category;
        console.log(ajaxurl);
        $.ajax({
            url: ajaxurl,
            success: function(result) {
                var subs = $('#category2sub1')
                subs.empty();
                for (var cat in result) {
                    console.log(result[cat]);
                    subs.append($("<option></option>")
                        .attr("value", result[cat]['rubrieknummer'])
                        .text(result[cat]['rubrieknaam']));
                }

                $("#category2sub4").empty();
                $("#category2sub3").empty();
                $("#category2sub2").empty();
                subs.trigger("change");

            }
        });

    });

    $("#category2sub1").change(function () {
        console.log("called");
        var category = $(this).val();
        //http://localhost:8080/inc/categories.php?cat=160
        var ajaxurl = "inc/categories.php?cat=" + category;
        console.log(ajaxurl);
        $.ajax({
            url: ajaxurl,
            success: function(result) {
                var subs = $('#category2sub2')
                subs.empty();
                for (var cat in result) {
                    console.log(result[cat]);
                    subs.append($("<option></option>")
                        .attr("value", result[cat]['rubrieknummer'])
                        .text(result[cat]['rubrieknaam']));
                }

                $("#category2sub4").empty();
                $("#category2sub3").empty();
                subs.trigger("change");

            }
        });

    });

    $("#category2sub2").change(function () {
        console.log("called");
        var category = $(this).val();
        //http://localhost:8080/inc/categories.php?cat=160
        var ajaxurl = "inc/categories.php?cat=" + category;
        console.log(ajaxurl);
        $.ajax({
            url: ajaxurl,
            success: function(result) {
                var subs = $('#category2sub3')
                subs.empty();
                for (var cat in result) {
                    console.log(result[cat]);
                    subs.append($("<option></option>")
                        .attr("value", result[cat]['rubrieknummer'])
                        .text(result[cat]['rubrieknaam']));
                }

                $("#category2sub4").empty();
                subs.trigger("change");
            }
        });

    });

    $("#category2sub3").change(function () {
        console.log("called");
        var category = $(this).val();
        //http://localhost:8080/inc/categories.php?cat=160
        var ajaxurl = "inc/categories.php?cat=" + category;
        console.log(ajaxurl);
        $.ajax({
            url: ajaxurl,
            success: function(result) {
                var subs = $('#category2sub4')
                subs.empty();
                for (var cat in result) {
                    console.log(result[cat]);
                    subs.append($("<option></option>")
                        .attr("value", result[cat]['rubrieknummer'])
                        .text(result[cat]['rubrieknaam']));
                }
                subs.trigger("change");
            }
        });
    });

    $('#categorieaantal').change(function() {
        if ($(this).val() == 2) {
            $('.kanonzichtbaar').each(function() {
                $(this).removeClass('cat2');
                console.log(this);
            });
        }
        else {
            $('.kanonzichtbaar').each(function() {
                $(this).addClass('cat2');
            });
        }
    });
});


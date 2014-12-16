$(document).ready(function() {
    $(".hdclick").click(function () {
        var category = $(this).attr('id').substring(2);
        $("#category").val(category);
    });
});
$('#feedback-modal').on('show.bs.modal', function (e) {
    var ajaxurl = "inc/modalinfo.php?veiling=" + $(e.relatedTarget).attr('id');
    console.log(ajaxurl);
    var modal = $("#modalmain");
    modal.hide();
    modal.toggleClass("blur");

    $.ajax({
        url: ajaxurl, success: function (result) {
            $('#modaltitel').html(result['titel']);
            $('#modalform').attr('action', '?page=mijnaccount&veiling=' + result['voorwerpnummer']);
            //console.log("DONE");
        },
        complete: function () {
        }
    });
});
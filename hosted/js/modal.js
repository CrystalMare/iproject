$('#feedback-modal').on('show.bs.modal', function (e) {
    var ajaxurl = "inc/modalinfo.php?veiling=" + $(e.relatedTarget).attr('id');
    var modal = $("#modalmain");
    modal.hide();
    modal.toggleClass("blur");

    $.ajax({
        url: ajaxurl, success: function (result) {
            $('#modaltitel').html(result['titel']);
            $('#veilingid').attr('value', result['voorwerpnummer']);
            console.log("DONE");
        },
        complete: function () {
        }
    });
});
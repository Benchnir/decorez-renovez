$(document).ready(function() {
    // a workaround for a flaw in the demo system (http://dev.jqueryui.com/ticket/4375), ignore!
    $("#dialog:ui-dialog").dialog("destroy");
		
    var reason = $("#reportform-reason"),
    message = $("#reportform-message"),
    author = $("#reportform-author"),
    service = $("#reportform-service"),
    allFields = $([]).add(reason).add(message);
    $("#dialog-form-report-service-ok").dialog({
        autoOpen: false,
        height: 300,
        width: 350,
        modal: true,
        resizable: false,
        title: "Merci !",
        buttons: {
            "Ok": function() {
                $(this).dialog("close");
            }
        }
    });
    $("#dialog-form-report-service").dialog({
        autoOpen: false,
        height: 300,
        width: 350,
        modal: true,
        resizable: false,
        buttons: {
            "Signaler": function() {
                var bValid = true;
                allFields.removeClass("ui-state-error");

                $("#dialog-form-report-service").dialog("close");
                if (bValid) {
                    $.ajax({
                        url: '/service/report',
                        data: $('#reportform-form').serialize(),
                        type: "POST"
                    })
                    .done(function(data) {
                        if (data == 'ok')
                        { 
                            $("#dialog-form-report-service-ok").dialog("open");
                        }
                    });
                   
                }
            },
            "Annuler": function() {
                $(this).dialog("close");
            }
        },
        close: function() {
            allFields.val("").removeClass("ui-state-error");
        }
    });

    $("#report-service")
    .click(function() {
        $("#dialog-form-report-service").dialog("open");
    });
});
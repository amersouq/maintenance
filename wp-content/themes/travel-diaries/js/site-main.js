$('document').ready(function () {
    $('.one_employee_manpower tbody tr').hover(function () {
                $(this).addClass("manpower_row_hover");
            },
            function () {
                $(this).removeClass("manpower_row_hover");
            }
    );
    $('body').on('click', '#print_ticket_href', function (e) {
        e.stopPropagation();
        event.preventDefault();
        //PrintElem('.container-fluid');
        $(".page").print({
//            globalStyles: true,
            mediaPrint: false,
            noPrintSelector: ".no-print",
            iframe: false,
            append: null,
            prepend: null,
            manuallyCopyFormValues: true,
            deferred: $.Deferred(),
            timeout: 250,
            title: null,
            doctype: '<!doctype html>'
        });

    });

    $('body').on('submit', '#change_password_form', function (e) {
        e.preventDefault();
        $('#loadingmessage').show();
        var url = $("#url").val();
        $("#loadingmessage").css("height", $(".col-xs-12").height());
        if ($("#password").val() == $("#confirm_password").val()) {
            $.ajax({
                type: "POST",
                url: url,
                data: $("#change_password_form").serialize(),
                success: function (data)
                {
                    if (data) {
                        alert("تم تغيير كلمة المرور بنجاح");
                        window.location.replace($("#redirect_url").val());
                    } else if (data = "confirmation-false") {
                        alert("برجاء إدخال التأكيد الصحيح لكلمة المرور");
                    }
                    $('#loadingmessage').hide();
//                    $("#change_password_form").load(location.href + " #change_password_form");
                }
            });
        } else {
            $('#loadingmessage').hide();
            alert("برجاء إدخال التأكيد الصحيح لكلمة المرور");
        }
    });

    $('body').on('click', '.filter_list', function (e) {
        e.stopPropagation();
        e.preventDefault();
        $("#ajax_container").fadeOut();
        $("#loadingmessage").css("height", $(".col-xs-12").height());
        $('#loadingmessage').show();
        var that = this;
        url = $(this).data("url");
        $.ajax({
            context: this,
            type: "GET",
            url: url,
            success: function (data)
            {

                $(".filter_list").removeClass("bordered-segment");
                $("#ajax_container").html(data);
                $('#loadingmessage').hide();
                $(that).addClass("bordered-segment");
                $("#ajax_container").fadeIn();
            }
        });
    });

    $('body').on('click', '.emp_filter_list', function (e) {
        e.stopPropagation();
        e.preventDefault();
        $("#ajax_container").fadeOut();
        var that = this;
        $('#loadingmessage').show();
        $("#loadingmessage").css("height", $(".col-xs-12").height());
        url = $(this).data("url");
        console.log(url);
        $.ajax({
            type: "GET",
            url: url,
            success: function (data)
            {
                $("#add_ticket_link").removeClass("bordered-segment");
                $(".emp_filter_list").removeClass("bordered-segment");
                $('#loadingmessage').hide();
                $(that).addClass("bordered-segment");
                $("#ajax_container").html(data);
                $("#ajax_container").fadeIn();
            }
        });
    });

    $("body").on("click", ".pagination a", function (e) {
        e.preventDefault();
        $("#ajax_container").fadeOut();
        $("#loadingmessage").css("height", $(".col-xs-12").height());
        $("#loadingmessage p").css("top", '50%');
        url = $("#hiddenURL").val();
        console.log(url);
        $('#loadingmessage').show();
        var page = $(this).attr("data-page"); //get page number from link
        $("#ajax_container").load(url, {"page": page}, function () { //get content from PHP page
            console.log(url);
            $("#loadingmessage").hide(); //once done, hide loading element
            $("#ajax_container").fadeIn();
        });
    });

    setInterval(function () {
        $(".new_ticket_label").toggle();
    }, 500);

    $('body').on('change', '#building_report_select', function (e) {
        e.preventDefault(); // avoid to execute the actual submit of the form.
        $('#loadingmessage').show();
        var that = this;
        var selectedBuilding = $(this).val();
        console.log(selectedBuilding);
        var url = $("#buildings_url").val() + "?buildingID=" + selectedBuilding;
        console.log(url);
        window.location.replace(url);
    });

    $('body').on('click', '#add_ticket_link', function (e) {
        e.stopPropagation();
        e.preventDefault();
        $("#ajax_container").fadeOut();
        var that = this;
        $("#loadingmessage").css("height", $(".col-xs-12").height());
        $('#loadingmessage').show();
        url = $("#hiddenURLAddTicket").val();
        console.log(url);
        $.ajax({
            type: "GET",
            url: url,
            success: function (data)
            {
                $(".emp_filter_list").removeClass("bordered-segment");
                $('#loadingmessage').hide();
                $(that).addClass("bordered-segment");
                $("#ajax_container").html(data);
                $("#ajax_container").fadeIn();
//                $("#loadingmessage").css("height", "100%");
            }
        });
    });

    $('body').on('submit', '#add_ticket_form', function (e) {
        e.preventDefault();
        $("#loadingmessage").css("height", $(".col-xs-12").height());
        $('#loadingmessage').show();
        var url = $("#url").val();
        var redirectURL = $("#redirectURL").val();
        $.ajax({
            type: "POST",
            url: url,
            data: $("#add_ticket_form").serialize(),
            success: function (data)
            {
                if (data) {
                    $("#ajax_container").fadeOut();
                    var that = this;
                    console.log(url);
                    $.ajax({
                        type: "GET",
                        url: redirectURL,
                        success: function (data)
                        {
                            $("#alert_container").show();
                            $("#add_ticket_link").removeClass("bordered-segment");
                            $(".emp_filter_list").removeClass("bordered-segment");
                            $('#loadingmessage').hide();
                            $("#all_tickets_link").addClass("bordered-segment");
                            $("#ajax_container").html(data);
                            $("#ajax_container").fadeIn();
                        }
                    });
                } else if (data = "confirmation-false") {
                    alert("حدث خطأ, برجاء المحاولة مرة أخرى");
                }
                $('#loadingmessage').hide();
            }
        });
    });
    $(".closebtn").click(function(){
        $(".alert").alert("close");
    });
    $('body').on('change', '#region_select', function (e) {
        e.preventDefault(); // avoid to execute the actual submit of the form.
        $("#loadingmessage").css("height", $(".col-xs-12").height());
        $('#loadingmessage').fadeIn();
        var url = $("#buildings_url").val() + "?regionID=" + $(this).val(); // the script where you handle the form input.
        $.ajax({
            type: "GET",
            url: url,
            success: function (data)
            {
                $("#building").html(data);
                $('#loadingmessage').fadeOut();
            }
        });
    });
});
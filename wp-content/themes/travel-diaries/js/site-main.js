$('document').ready(function () {
    $('.one_employee_manpower tbody tr').hover(
            function () {
//                console.log("hover in");
                $(this).addClass("manpower_row_hover");
            },
            function () {
//                console.log("hover out");
                $(this).removeClass("manpower_row_hover");
            }
    );
    $('body').on('click', '#print_ticket_href', function (e) {
        e.stopPropagation();
        event.preventDefault();
        //PrintElem('.container-fluid');
        $("#page").print({
            globalStyles: true,
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

    $("#region_select").change(function (e) {
        e.preventDefault(); // avoid to execute the actual submit of the form.
        $('#loadingmessage').show();
        var url = $("#buildings_url").val() + "?regionID=" + $(this).val(); // the script where you handle the form input.
        console.log(url);
        $.ajax({
            type: "GET",
            url: url,
            success: function (data)
            {
                console.log(data); // show response from the php script.
                $("#building").html(data);
                $('#loadingmessage').hide();
            }
        });
    });
    $('body').on('submit', '#change_password_form', function (e) {
        e.preventDefault();
        var url = $("#url").val();
        if ($("#password").val() == $("#confirm_password").val()) {
            $.ajax({
                type: "POST",
                url: url,
                data: $("#change_password_form").serialize(),
                success: function (data)
                {
                    console.log(data); // show response from the php script.
                    console.log($("#redirect_url").val());
                    if(data){
                        alert("تم تغيير كلمة المرور بنجاح");
                        window.location.replace($("#redirect_url").val());
                    }else if(data = "confirmation-false"){
                        alert("برجاء إدخال التأكيد الصحيح لكلمة المرور");
                    }
                    
//                    $("#change_password_form").load(location.href + " #change_password_form");
                }
            });
        }else{
            alert("برجاء إدخال التأكيد الصحيح لكلمة المرور");
        }
    });

    setInterval(function () {
        $(".new_ticket_label").toggle();
    }, 500);
});
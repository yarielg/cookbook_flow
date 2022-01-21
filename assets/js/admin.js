jQuery(function($) {

    $('#btn_generate_cookbook_file').on('click',function(){
        var order_id = $(this).data('order_id');
        var cookbook_id = $(this).data('cookbook_id');
        $.ajax( {
            type: 'POST',
            url:  parameters.ajax_url,
            data:{
                'action':'generate_xml_files',
                'cookbook_id' : cookbook_id,
                'order_id' : order_id,
            },
            dataType: "json",
            beforeSend: function () {

            },
            complete: function () {

            },
            success: function (response) {
                if(response.success) {
                    alert('file generated');
                }else{
                    alert(response.msg);
                }

                //location.reload();

            },
            error : function(jqXHR, exception){
                var msg = '';
                if (jqXHR.status === 0) {
                    msg = 'Not connect.\n Verify Network.';
                } else if (jqXHR.status == 404) {
                    msg = 'Requested page not found. [404]';
                } else if (jqXHR.status == 500) {
                    msg = 'Internal Server Error [500].';
                } else if (exception === 'parsererror') {
                    msg = 'Requested JSON parse failed.';
                } else if (exception === 'timeout') {
                    msg = 'Time out error.';
                } else if (exception === 'abort') {
                    msg = 'Ajax request aborted.';
                } else {
                    msg = 'Uncaught Error.\n' + jqXHR.responseText;
                }
                alert(msg);
            }

        });
    });


});

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

                location.reload();

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

    $('#cookbook_send_comment').on('click', function(e){
        var comment = $('#cbf_message_value').val().trim();
        var admin = $(this).data('admin');
        var cookbook_id = $(this).data('cookbook_id');

        if(comment == ''){
            return;
            console.log(comment)
        }

        $.ajax( {
            type: 'POST',
            url:  parameters.ajax_url,
            data:{
                'action':'add_comment',
                'cookbook_id' : cookbook_id,
                'admin' : admin,
                'comment': comment
            },
            dataType: "json",
            beforeSend: function () {

            },
            complete: function () {

            },
            success: function (response) {
                if(response.success) {
                    $('#cbf_message_value').val('')
                    getComments(cookbook_id,1);
                }else{
                    alert(response.msg);
                }
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


    setInterval(function(){
        var cookbook_id = $('#cookbook_send_comment').data('cookbook_id');
        getComments(cookbook_id,1);
    },5000)

    function getComments(cookbook_id,admin){
        $.ajax( {
            type: 'POST',
            url:  parameters.ajax_url,
            data:{
                'action':'get_comments',
                'cookbook_id' : cookbook_id,
                'admin' : admin
            },
            dataType: "json",
            beforeSend: function () {

            },
            complete: function () {

            },
            success: function (response) {
                if(response.success) {
                    renderComments(response.comments)
                    $('.chat_canvas').scrollTop($('.chat_canvas').prop('scrollHeight'))
                }else{
                    alert(response.msg);
                }
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
    }

    function renderComments(comments){
        var $canvas = $('.chat_canvas');
        var position_class = '';
        $canvas.empty();
        var previuos_day = 0;
        for (var i=0; i < comments.length; i++){
            var timestamp = comments[i].created;
            var date = new Date(timestamp);

            var year = date.getFullYear();
            var month = date.getMonth() + 1;
            var day = date.getDate();
            var hours = date.getHours();
            var minutes = date.getMinutes();
            var formated = hours + ":" + minutes;
            var formatedDate = year + "-" + month + "-" + day;

            if(day > previuos_day){
                previuos_day = day;
                $canvas.append('<p class="cbf-comment date center">' + formatedDate +'</p>')
            }

            position_class = comments[i].admin == 1 ? 'right' : 'left';
            $canvas.append('<p class="cbf-comment '+ position_class+'">' + comments[i].comment + '<span class="time">'+ formated +'</span></p>')
        }
    }


});

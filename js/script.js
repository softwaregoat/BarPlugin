jQuery(document).ready(function ($) {
    $('#image_upload').click(function () {
        var formData = new FormData();
        formData.append('file', $('#icon-upload')[0].files[0]);
        formData.append('action', 'upload_image');
        $.ajax({
            url : mine.ajaxurl,
            type : 'POST',
            data : formData,
            processData: false,  // tell jQuery not to process the data
            contentType: false,  // tell jQuery not to set contentType
            success : function(data) {
                if (data != 'FAILED') {
                    $('#image').val(data);
                }
            }
        });
    });
    $('#copylastpost').click(function () {
        if($(this).is(":checked"))
        {
            let formData = new FormData();
            formData.append('action', 'get_last_metadata');
            $.ajax({
                url : mine.ajaxurl,
                type : 'POST',
                data : formData,
                processData: false,  // tell jQuery not to process the data
                contentType: false,  // tell jQuery not to set contentType
                success : function(data) {
                    let json = $.parseJSON(data);
                    $('#image').val(json.image);
                    $('#buttonText').val(json.buttonText);
                    $('#destinationURL').val(json.destinationURL);
                    $('#headline').val(json.headline);
                }
            });
        }
        else{
            $('#image').val('');
            $('#buttonText').val('');
            $('#destinationURL').val('');
            $('#headline').val('');
        }
    });
    var scroll = $('#scroll').val();
    if (scroll ==''){
        $('#cpt-bar').css('display', 'flex');
    }
    $(document).scroll(function() {
        if (scroll =='')return;
        scroll = parseInt(scroll);
        var y = $(this).scrollTop();
        if (y > scroll) {
            $('#cpt-bar').fadeIn();
        } else {
            $('#cpt-bar').fadeOut();
        }
    });
});
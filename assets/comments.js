$(function () {
    var xhr;
    $(document).on('click', '.comment-delete', function (e) {

        if (typeof xhr !== 'undefined')
            xhr.abort();

        xhr = $.ajax({
            url: $(this).attr('href'),
            dataType: 'json',
            type: 'POST',
            success: function (data) {
                console.log(data);
                if (data.status === 'success') {
                    common.notify(data.message, 'success');
                    $.pjax.reload({container: '#pjax-comments', async: false});
                }
            }
        });
        e.preventDefault();
        return false;
    });


    $(document).on('click', '.comment-update', function (e) {
        var that = $(this);
        if (typeof xhr !== 'undefined')
            xhr.abort();

        xhr = $.ajax({
            url: that.attr('href'),
            dataType: 'json',
            type: 'POST',
            success: function (data) {
                console.log(data);
                if (data.status === 'success') {
                    common.notify(data.message, 'success');
                    console.log(that.data('id'));
                    $('#comment_text_' + that.data('id')).html(data.result);
                    //$.pjax.reload({container: '#pjax-comments', async: false});
                }
            }
        });
        e.preventDefault();
        return false;
    });


    $(document).on('click', '.comment-reply', function (e) {
        var that = $(this);
        if (typeof xhr !== 'undefined')
            xhr.abort();

        xhr = $.ajax({
            url: that.attr('href'),
            dataType: 'html',
            type: 'GET',
            success: function (data) {
                console.log('.comment-reply');
                //$('#test' + that.data('id')).html(data);
                //$('.container-reply').html(data);
                $('#container-reply-'+that.data('id')).html(data);


            }
        });
        e.preventDefault();
        return false;
    });


    /**
     * Submit reply form
     */
    $(document).on('submit', 'form#comment-reply-form', function () {
        var form = $(this);
        // return false if form still have some validation errors
        if (form.find('.has-error').length) {
            return false;
        }
        // submit form
        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            //dataType:'json',
            data: form.serialize(),
            success: function (response) {

                // $.pjax.reload('#note_update_id'); for pjax update
                //$('#comment-reply-form').html(response);
                //console.log(getupdatedata);
                $.pjax.reload({container: '#pjax-comments', async: false});
            },
            error: function () {
                console.log('internal server error');
            }
        });
        return false;
    });
});
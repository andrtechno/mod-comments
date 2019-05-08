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
});
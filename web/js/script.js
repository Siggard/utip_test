var timerId = undefined;

/**
 * @param id
 */
function searchFiles(id, searchString)
{
    if (timerId !== undefined) {
        clearTimeout(timerId);
    }

    timerId = setTimeout(function() {
        $('#' + id).addClass('fileupload-processing');
        $.ajax({
            url: $('#' + id).fileupload('option', 'url') + '&like=' + searchString,
            dataType: 'json',
            context: $('#' + id)[0]
        }).always(function () {
            $(this).removeClass('fileupload-processing');
        }).done(function (result) {
            $('#' + id).find('.files').empty();
            $(this).fileupload('option', 'done').call(this, $.Event('done'), {result: result});
        });
    }, 1000);
}
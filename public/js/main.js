$(document).ready(function () {

    $('#newRecordForm').submit('click', function () {
        event.preventDefault();
        formData = {
            content: this.content.value,
            _token: this._token.value
        };
        $.ajax({
            url: this.action,
            type: 'POST',
            data: formData,

            success: function (response) {

                console.log(response)
            }
        })
    });

    $('.deleteRecordForm').submit('click', function () {
        event.preventDefault();
        $.ajax({
            url: this.action,
            type: 'delete',
            success: function (response) {
            }
        });
        console.log(this.id.value);
        $('#record-' + this.id.value).remove();
    });

    $('#formLoginSubmit').on('click', function () {
        $('#login').submit();

    });
    $('#submitRegister').on('click', function () {
        $('#registration').submit();

    });


    $('#submitEditRecord').on('click', function () {
        form = $('#editRecordForm')[0];
        formData = {
            content: form.content.value
        };

        $.ajax({
            url: action,
            type: 'POST',
            data: formData,
            success: function (response) {
            }
        });
        $('#editRecordModal').modal('hide');
    });

    $('.showEditForm').on('click', function () {

        recordId = $(this).attr('data-record-id');

        contentRecord = $('#content-record-' + recordId).text();
        action = 'records/' + recordId;
        $('editRecordForm').attr('action', action);

        $('#editContentArea').text(contentRecord);

        $('#editRecordModal').modal('show');
    });

    $('.showLogin').on('click', function () {
        $('#loginModal').modal('show');
    });

    $('.showRegister').on('click', function () {
        $('#loginModal').modal('hide');
        $('#registerModal').modal('show');
    });


    $('#toRegistration').on('click', function () {
        console.log('to reg');
        $('#loginModal').modal('hide');
        $('#registerModal').modal('show');
    });

    $('#toAuthorisation').on('click', function () {
        console.log('to auth');
        $('#registerModal').modal('hide');
        $('#loginModal').modal('show');
    });

    window.Echo = new Echo({
        broadcaster: 'socket.io',
        host: window.location.hostname + ':6001'
    });
    window.Echo.channel('private-records').listen('NewRecord', function (event) {
        console.log(event);
        if ($("#record-" + event.record.id).is('.media')) {
            contentRecordId = '#content-record-' + event.record.id;
            $(contentRecordId).html(event.record.content);

        } else {
            console.log(event.record.content);
            newRecord = '<div class="media-body"> <h5 class="mt-0">' + event.record.user.name + '</h5>'
                + event.record['content'] + "  </div>";
            $('#MediaList').prepend(newRecord);
        }

    })

});





$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
})

// document ready start 
jQuery(document).ready(function () {

    // initiate new chat
    $('.chat-item').on('click', function () {

        $('#chat-container').html('');
        var getUserId = $(this).attr('id');
        receiver_id = getUserId;

        $.ajax({
            url: '/get-user',
            type: 'GET',
            data: { receiver_id: receiver_id },
            success: function (res) {
                $('#user-profile').attr('src', res.data.image ? res.data.image : "images/dummy.avif");
                $('#user-name').html(res.data.name);
            }
        });

        $('.start-head').hide('slow');
        $('.chat-section').show('slow');

        loadOldChats();
        scrollChat();
    });


    // save new message 
    $('#chat-form').submit(function (e) {
        e.preventDefault();
        var message = $('#message').val();

        $.ajax({
            url: '/save-chat',
            type: "POST",
            data: { sender_id: sender_id, receiver_id: receiver_id, message: message },
            success: function (res) {

                if (res.success) {
                    $('#message').val('');

                    let chat = res.data.message;
                    let html = `<div class="current-user-chat mb-1" id="${res.data.id}-chat">
                                <span class="current-user-chat-wrapper">${chat}</span>
                                <i class="fa fa-trash fa-xs" aria-hidden="true" data-bs-toggle="modal" 
                                data-id="${res.data.id}"
                                data-bs-target="#deleteChatModal"></i>
                                </div>`;

                    $('#chat-container').append(html);
                    scrollChat();

                } else {
                    alert('msg');
                }
            }
        })
    })


    // load old chats
    function loadOldChats() {
        $.ajax({
            url: "/load-chats",
            type: "POST",
            data: { sender_id: sender_id, receiver_id: receiver_id },
            success: function (res) {

                if (res.success) {
                    let chats = res.data;
                    var html = '';

                    for (let i = 0; i < chats.length; i++) {

                        let addContainerClass = '';
                        let addWrapperClass = '';

                        if (chats[i].sender_id == sender_id) {
                            addContainerClass = 'current-user-chat mb-1'
                            addWrapperClass = 'current-user-chat-wrapper'
                        } else {
                            addContainerClass = 'distance-user-chat mb-1'
                            addWrapperClass = 'distance-user-chat-wrapper'
                        }

                        var chat_id = chats[i].id + "-chat";
                        let html = `<div class="${addContainerClass}" id="` + chat_id + `">`
                        html += `<span class="${addWrapperClass}">`;
                        html += `${chats[i].message}</span>`;
                        html += chats[i].sender_id === sender_id ?
                            `<i class="fa fa-trash fa-xs" 
                                data-bs-toggle="modal" 
                                data-id="${chats[i].id}"
                                data-bs-target="#deleteChatModal"></i></div>` : `</div>`;

                        $('#chat-container').append(html);
                        scrollChat();
                    }
                } else {
                    alert(res.msg);
                }
            }
        })
    }// load old chats ends 


    $(document).on('click', '.fa-trash', function () {
        var id = $(this).attr('data-id');
        let message = $(this).parent().prev().text();
        $('#delete-chat-id').val(id);
        $('#delete-message').text(message);
    })


    $('#delete-chat-form').submit((e) => {
        e.preventDefault();

        var id = $('#delete-chat-id').val();
        console.log(id);

        $.ajax({
            url: "/delete-chat",
            type: "POST",
            data: { id: id },
            success: function (res) {
                alert(res.msg);
                if (res.success) {
                    $(`#${id}-chat`).remove();
                    $('#deleteChatModal').modal('hide');
                }
            }
        })

    })

})// document ready ends 


// scroll chats to bottom
function scrollChat() {
    $('#chat-container').animate({
        scrollTop: $('#chat-container').offset().top + $('#chat-container')[0].scrollHeight
    }, 0);
}


// User online offline status update event
Echo.join('status-update')
    .here((users) => {
        for (let i = 0; i < users.length; i++) {
            if (sender_id != users[i]['id']) {
                $('#' + users[i]['id'] + '-status').removeClass('offline-status');
                $('#' + users[i]['id'] + '-status').addClass('online-status');
                $('#' + users[i]['id'] + '-status').text('online');
            }
        }
    })
    .joining((user) => {
        $('#' + user.id + '-status').removeClass('offline-status');
        $('#' + user.id + '-status').addClass('online-status');
        $('#' + user.id + '-status').text('online');
    })
    .leaving((user) => {
        $('#' + user.id + '-status').removeClass('online-status');
        $('#' + user.id + '-status').addClass('offline-status');
        $('#' + user.id + '-status').text('offline');
    })
    .listen('UserStatusEvent', (e) => {
        console.log('asdf' + e);
    })


// Receive broadcasted message
Echo.private('broadcast-message')
    .listen('.getChatMessage', (data) => {
        if (sender_id == data.chat.receiver_id && receiver_id == data.chat.sender_id) {
            console.log(data);
            let html = `<div class="distance-user-chat mb-1 " id="${data.chat.id}-chat">`
            html += `<span class="distance-user-chat-wrapper">`
            html += `${data.chat.message}</span></div>`;

            $('#chat-container').append(html);
            scrollChat();
        }
    });


// Listen Delete Message Event
Echo.private('message-deleted')
    .listen('MessageDeleteEvent', (data) => {
        console.log(data);
        $(`#${data.id}-chat`).remove();
        $('#deleteChatModal').modal('hide');
    });
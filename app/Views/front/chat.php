<div class="row">
    <div class="col">
        <h1>Ma messagerie</h1>
    </div>
</div>
<div style="height: 80vh !important;">
    <div class="row h-100">
        <!--START: HISTORIQUE -->
        <div class="col-md-3 h-100">
            <div class="card h-100">
                <div class="card-body overflow-auto">
                    <div class="">
                        <select name="receiver" id="receiver" class="form-select">
                        </select>
                    </div>
                    <div id="historique">
                        <?php foreach($historique as $histo) : ?>
                            <div class="card mt-3" data-id="<?= $histo['id']; ?>">
                                <div class="card-body">
                                    <?= $histo['username']; ?><br>
                                    <?= $histo['last_message']; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
        <!--END: HISTORIQUE -->
        <!--START: ZONE MESSAGE -->
        <div class="col h-100">
            <div class="card h-100 " id="zone-message">
                <div class="card-body overflow-auto my-3">
                    <div class="row" id="messages">

                    </div>
                </div>
                <div class="card-footer">
                    <div class="row">
                        <div class="col-9">
                            <textarea name="message" id="message" class="form-control"></textarea>
                        </div>
                        <div class="col d-grid align-items-center">
                            <span class="btn btn-primary" id="send-message">Envoyer</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--END: ZONE MESSAGE -->
    </div>
</div>
<script>
    $(document).ready(function() {
        const base_url = "<?= base_url() ?>";
        const $messages = $('#messages');
        var sender = <?= $session_user->id; ?>;
        var receiver = null;
        var page;
        var max_page;
        var last_message_date;

        //Ajout du SELECT2 à notre select destinataire (receiver)
        initAjaxSelect2("#receiver",{
            url: base_url + 'api/user/all',
            placeholder: "Choisir un destinataire",
            searchFields: ['username'],
            delay : 250
        });
        // Fonction réutilisable pour charger une conversation
        function loadConversation(receiverId) {
            page = 1;
            receiver = receiverId;
            $.ajax({
                type: 'GET',
                url: base_url + 'messagerie/conversation',
                data: {
                    id_1: sender,
                    id_2: receiver,
                    page: page
                },
                success: function (data_full) {
                    var data = data_full.data;
                    max_page = data_full.max_page;
                    $('#messages').empty();
                    last_message_date = data[0].created_at;

                    data.forEach(function (msg) {
                        const isSender = msg.id_sender == sender;
                        $messages.prepend(addMessage(msg.content, msg.created_at, isSender));
                    });

                    var $container = $('#zone-message .card-body');
                    $container.scrollTop($container[0].scrollHeight);
                },
                error: function (err) {
                    console.error(err);
                }
            });

        }
        // Événement au choix d'un destinataire
        $('#receiver').on('select2:select', function (e) {
            const receiverId = e.params.data.id;
            loadConversation(receiverId);
        });
        // Événement au clic sur une conversation dans l'historique
        $('#historique').on('click', '.card', function () {
            const receiverId = $(this).data('id');
            $('.active-conversation').removeClass('active-conversation');
            $(this).addClass('active-conversation');
            loadConversation(receiverId);
        });
        $("#historique .card").first().trigger('click');

        //Événement au clic de l'envoi du message
        $('#send-message').on('click', function(){
            var message = $('#message').val();
            // console.log("sender : " + sender);
            // console.log("receiver : " + receiver);
            // console.log("message : " + message);
            $.ajax({
                'type': 'POST',
                'url' : base_url + 'messagerie/send',
                'data' : {
                    id_sender : sender,
                    id_receiver : receiver,
                    content : message
                },
                'success' : function(data){
                    console.log(data);
                    if(data.success) {
                        $messages.append(addMessage(data.data.content,"à l'instant",true));
                        $('#message').val('');
                    }
                },
                'error' : function(data){
                    console.log(data);
                }
            })
        });
        //Événement au scroll de la zone de message
        $('#zone-message .card-body').on('scroll', function() {
            if($(this).scrollTop() == 0) {
                page++;
                if(page <= max_page) {
                    $.ajax({
                        'type': 'GET',
                        'url' : base_url + 'messagerie/conversation',
                        'data' : {
                            'id_1' : sender,
                            'id_2' : receiver,
                            'page' : page
                        },
                        'success' : function(data_full){
                            var data = data_full.data;
                            for(var i = 0; i < data.length; i++) {
                                if(data[i].id_sender == sender) {
                                    $messages.prepend(addMessage(data[i].content,data[i].created_at, true));
                                } else {
                                    $messages.prepend(addMessage(data[i].content,data[i].created_at, false));
                                }
                            }
                            var $container = $('#zone-message .card-body');
                            $container.scrollTop(150);
                        },
                        'error' : function(data){
                            console.log(data);
                        }
                    });
                } else {
                    $('#messages').prepend('<div class="col-md-12"><div class="alert alert-info">Fin de la conversation</div></div>');
                }
            }
        });
        //Execute à un interval régulier
        setInterval(checkNewMessage, 3000);

        //Fonction de verification des nouveaux messages
        function checkNewMessage() {
            $.ajax({
                'type': 'GET',
                'url' : base_url + 'messagerie/new-messages',
                'data' : {
                    'id_1' : sender,
                    'id_2' : receiver,
                    'date' : last_message_date
                },
                'success' : function(data){
                    for(var i = 0; i < data.length; i++) {
                        $messages.append(addMessage(data[i].content,data[i].created_at, false));
                        last_message_date = data[i].created_at;
                        var $container = $('#zone-message .card-body');
                        $container.scrollTop($container[0].scrollHeight);
                    }
                },
                'error' : function(data){
                    console.log(data);
                }
            });
            $.ajax({
                'type': 'GET',
                'url' : base_url + 'messagerie/historique',
                'data' : {
                    'id' : sender,
                    'date' : last_message_date
                },
                'success' : function(data){
                    $('#historique').empty();
                    var arret = false;
                    for(i = 0; i < data.length; i++) {
                        var active = '';
                        if(data[i].id == receiver) {
                            active = 'active-conversation';
                            arret = true;
                        }
                        if(arret == false) {
                            var new_message = 'new-message';
                        } else {
                            var new_message = '';
                        }
                        var histo = `
                            <div class="card mt-3 ${active} ${new_message}" data-id="${data[i].id}">
                                <div class="card-body">
                                    ${data[i].username}<br>
                                    ${data[i].last_message}
                                </div>
                            </div>
                        `;
                        $('#historique').append(histo);
                        console.log(data[i].id);
                    }
                },
                'error' : function(data){
                    console.log(data);
                }
            })
        }
        //Fonction d'ajout d'un message dans la zone de message
        function addMessage(message, date = '', sender = false){
            var color ='success';
            var offset ='';
            if(sender == true) {
                color = 'primary';
                offset = 'offset-5';
            }
            var msg = `
                <div class="col-7 ${offset}">
                    <div class="alert alert-${color}">
                        ${message}
                    </div>
                    <span class="text-muted">${date}</span>
                </div>
            `;
            return msg;
        }
    });
</script>
<style>
    #historique .card:hover {
        cursor: pointer;
        border-color: var(--bs-primary);
        transform: scale(1.05);
    }

    .active-conversation {
        border-color: var(--bs-primary) !important;
    }

    .new-message {
        background-color: var(--bs-primary) !important;
    }
</style>
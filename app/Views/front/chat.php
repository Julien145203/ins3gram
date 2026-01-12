<div class="row">
    <div class="col">
        <h1>Ma messagerie</h1>
    </div>
</div>

<div style="height: 80vh !important;">
    <div class="row h-100">
        <!-- Colonne gauche : Historique des conversations -->
        <div class="col-md-3 h-100">
            <div class="card h-100">
                <div class="card-header">
                    <h5>Conversations</h5>
                </div>
                <div class="card-body overflow-auto">
                    <!-- Select pour choisir un nouveau destinataire -->
                    <div class="mb-3">
                        <label for="receiver" class="form-label">Nouveau message</label>
                        <select name="receiver" id="receiver" class="form-select">
                            <option value="">Choisir un destinataire...</option>
                        </select>
                    </div>

                    <hr>

                    <!-- Liste des conversations existantes -->
                    <div id="historique">
                        <?php if(!empty($historique)): ?>
                            <?php foreach($historique as $histo): ?>
                                <div class="card mt-2 conversation-item"
                                     data-id="<?= $histo['id']; ?>"
                                     style="cursor: pointer;">
                                    <div class="card-body p-2">
                                        <strong><?= esc($histo['username']); ?></strong>
                                        <br>
                                        <small class="text-muted">
                                            <?= date('d/m/Y H:i', strtotime($histo['last_message'])); ?>
                                        </small>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="text-muted text-center">Aucune conversation</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Colonne droite : Zone de messages -->
        <div class="col-md-9 h-100">
            <div class="card h-100 d-flex flex-column" id="zone-message">
                <div class="card-header">
                    <h5 id="conversation-title">Sélectionnez une conversation</h5>
                </div>

                <!-- Zone d'affichage des messages -->
                <div class="card-body overflow-auto flex-grow-1" id="messages-container">
                    <div id="messages" class="d-flex flex-column gap-2">
                        <!-- Les messages seront chargés ici -->
                    </div>
                </div>

                <!-- Zone d'envoi de message -->
                <div class="card-footer">
                    <div class="row g-2">
                        <div class="col-9">
                            <textarea name="message"
                                      id="message"
                                      class="form-control"
                                      rows="2"
                                      placeholder="Tapez votre message..."
                                      style="resize: none;"></textarea>
                        </div>
                        <div class="col-3 d-flex align-items-center">
                            <button class="btn btn-primary w-100" id="send-message">
                                <i class="fas fa-paper-plane"></i> Envoyer
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        const base_url = "<?= base_url() ?>";
        const $messages = $('#messages');
        const $messagesContainer = $('#messages-container');

        // Variables globales
        const sender = <?= $session_user->id; ?>;
        let receiver = null;
        let page = 1;
        let max_page = 1;
        let last_message_date = null;
        let checkInterval = null;
        let selectedUsername = '';

        // Initialisation du Select2 pour choisir un destinataire
        $('#receiver').select2({
            theme: 'bootstrap-5',
            placeholder: 'Rechercher un utilisateur...',
            allowClear: true,
            ajax: {
                url: base_url + 'api/user/all',
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        search: params.term,
                        page: params.page || 1
                    };
                },
                processResults: function(data) {
                    return {
                        results: data.results,
                        pagination: {
                            more: data.pagination.more
                        }
                    };
                },
                cache: true
            }
        });

        /**
         * Charge une conversation complète
         */
        function loadConversation(receiverId, username = '') {
            page = 1;
            receiver = receiverId;
            selectedUsername = username;

            // Met à jour le titre
            $('#conversation-title').text(username ? `Conversation avec ${username}` : 'Conversation');

            $.ajax({
                type: 'GET',
                url: base_url + 'messagerie/conversation',
                data: { id_1: sender, id_2: receiver, page: page },
                success: function(response) {
                    max_page = response.max_page;
                    const messages = response.data;

                    $messages.empty();

                    if (messages.length > 0) {
                        last_message_date = messages[0].created_at;

                        // Affiche les messages (ordre inverse car ORDER BY DESC dans la BDD)
                        messages.reverse().forEach(function(msg) {
                            const isSender = msg.id_sender == sender;
                            $messages.append(createMessageHTML(msg.content, msg.created_at, isSender));
                        });

                        scrollToBottom();
                    } else {
                        $messages.html('<p class="text-muted text-center">Aucun message</p>');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Erreur chargement conversation:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Erreur',
                        text: 'Impossible de charger la conversation'
                    });
                }
            });
        }

        /**
         * Crée le HTML d'un message
         */
        function createMessageHTML(content, date, isSender) {
            const alignClass = isSender ? 'ms-auto text-end' : '';
            const bgClass = isSender ? 'bg-primary text-white' : 'bg-light';
            const formattedDate = new Date(date).toLocaleString('fr-FR', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });

            return `
            <div class="d-flex ${isSender ? 'justify-content-end' : 'justify-content-start'}">
                <div class="message-bubble ${bgClass} p-2 rounded" style="max-width: 70%;">
                    <div>${content}</div>
                    <small class="text-muted d-block mt-1" style="font-size: 0.75rem;">
                        ${formattedDate}
                    </small>
                </div>
            </div>
        `;
        }

        /**
         * Scroll automatique vers le bas
         */
        function scrollToBottom() {
            $messagesContainer.scrollTop($messagesContainer[0].scrollHeight);
        }

        /**
         * Événement : sélection d'un destinataire dans le select
         */
        $('#receiver').on('select2:select', function(e) {
            const receiverId = e.params.data.id;
            const username = e.params.data.text;
            loadConversation(receiverId, username);
            startCheckingNewMessages();
        });

        /**
         * Événement : clic sur une conversation dans l'historique
         */
        $('#historique').on('click', '.conversation-item', function() {
            const receiverId = $(this).data('id');
            const username = $(this).find('strong').text();

            // Retire la classe active de toutes les conversations
            $('.conversation-item').removeClass('active-conversation');

            // Ajoute la classe active à celle sélectionnée
            $(this).addClass('active-conversation');

            loadConversation(receiverId, username);
            startCheckingNewMessages();
        });

        /**
         * Événement : envoi d'un message
         */
        $('#send-message').on('click', sendMessage);

        // Envoi avec la touche Entrée (Ctrl+Entrée pour nouvelle ligne)
        $('#message').on('keydown', function(e) {
            if (e.key === 'Enter' && !e.ctrlKey && !e.shiftKey) {
                e.preventDefault();
                sendMessage();
            }
        });

        function sendMessage() {
            const message = $('#message').val().trim();

            if (!message) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Attention',
                    text: 'Veuillez saisir un message',
                    timer: 2000
                });
                return;
            }

            if (!receiver) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Attention',
                    text: 'Veuillez sélectionner un destinataire',
                    timer: 2000
                });
                return;
            }

            $.ajax({
                type: 'POST',
                url: base_url + 'messagerie/send',
                data: {
                    id_sender: sender,
                    id_receiver: receiver,
                    content: message
                },
                success: function(response) {
                    if (response.success) {
                        $messages.append(createMessageHTML(
                            response.data.content,
                            new Date().toISOString(),
                            true
                        ));
                        $('#message').val('');
                        scrollToBottom();
                        updateHistorique();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Erreur',
                            text: 'Impossible d\'envoyer le message'
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Erreur envoi message:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Erreur',
                        text: 'Impossible d\'envoyer le message'
                    });
                }
            });
        }

        /**
         * Scroll infini : charge les messages précédents
         */
        $messagesContainer.on('scroll', function() {
            if ($(this).scrollTop() === 0 && page < max_page) {
                page++;
                loadMoreMessages();
            }
        });

        function loadMoreMessages() {
            $.ajax({
                type: 'GET',
                url: base_url + 'messagerie/conversation',
                data: { id_1: sender, id_2: receiver, page: page },
                success: function(response) {
                    const messages = response.data;
                    const oldHeight = $messagesContainer[0].scrollHeight;

                    messages.forEach(function(msg) {
                        const isSender = msg.id_sender == sender;
                        $messages.prepend(createMessageHTML(msg.content, msg.created_at, isSender));
                    });

                    // Maintient la position de scroll
                    const newHeight = $messagesContainer[0].scrollHeight;
                    $messagesContainer.scrollTop(newHeight - oldHeight);
                },
                error: function(xhr, status, error) {
                    console.error('Erreur chargement messages:', error);
                }
            });
        }

        /**
         * Vérifie les nouveaux messages toutes les 3 secondes
         */
        function startCheckingNewMessages() {
            // Arrête l'ancien intervalle s'il existe
            if (checkInterval) {
                clearInterval(checkInterval);
            }

            // Démarre un nouvel intervalle
            checkInterval = setInterval(checkNewMessages, 3000);
        }

        function checkNewMessages() {
            if (!receiver || !last_message_date) return;

            $.ajax({
                type: 'GET',
                url: base_url + 'messagerie/new-messages',
                data: {
                    id_1: sender,
                    id_2: receiver,
                    date: last_message_date
                },
                success: function(messages) {
                    if (messages.length > 0) {
                        messages.forEach(function(msg) {
                            $messages.append(createMessageHTML(msg.content, msg.created_at, false));
                            last_message_date = msg.created_at;
                        });
                        scrollToBottom();
                        updateHistorique();
                    }
                }
            });
        }

        /**
         * Met à jour l'historique des conversations
         */
        function updateHistorique() {
            $.ajax({
                type: 'GET',
                url: base_url + 'messagerie/historique',
                data: { id: sender },
                success: function(data) {
                    $('#historique').empty();

                    data.forEach(function(conv) {
                        const isActive = conv.id == receiver ? 'active-conversation' : '';
                        const formattedDate = new Date(conv.last_message).toLocaleString('fr-FR', {
                            day: '2-digit',
                            month: '2-digit',
                            year: 'numeric',
                            hour: '2-digit',
                            minute: '2-digit'
                        });

                        const html = `
                        <div class="card mt-2 conversation-item ${isActive}"
                             data-id="${conv.id}"
                             style="cursor: pointer;">
                            <div class="card-body p-2">
                                <strong>${conv.username}</strong>
                                <br>
                                <small class="text-muted">${formattedDate}</small>
                            </div>
                        </div>
                    `;
                        $('#historique').append(html);
                    });
                }
            });
        }

        // Charge la première conversation au démarrage (si elle existe)
        <?php if(!empty($historique)): ?>
        $('.conversation-item').first().trigger('click');
        <?php endif; ?>
    });
</script>

<style>
    /* Style des conversations dans l'historique */
    .conversation-item {
        transition: all 0.2s;
    }

    .conversation-item:hover {
        background-color: #f8f9fa;
        transform: translateX(5px);
        border-left: 3px solid var(--bs-primary);
    }

    .active-conversation {
        background-color: var(--bs-primary) !important;
        color: white !important;
        border-left: 3px solid var(--bs-dark);
    }

    .active-conversation strong,
    .active-conversation small {
        color: white !important;
    }

    /* Style des bulles de message */
    .message-bubble {
        word-wrap: break-word;
        border-radius: 15px !important;
    }

    /* Scrollbar personnalisée */
    #messages-container::-webkit-scrollbar {
        width: 8px;
    }

    #messages-container::-webkit-scrollbar-track {
        background: #f1f1f1;
    }

    #messages-container::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 4px;
    }

    #messages-container::-webkit-scrollbar-thumb:hover {
        background: #555;
    }
</style>
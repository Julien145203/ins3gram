$(document).ready(function () {
// Configuration de jQuery pour ajouter le token à chaque requête AJAX
    $.ajaxSetup({
        beforeSend: function (xhr) {
            xhr.setRequestHeader('X-CSRF-TOKEN', csrfHash);
        }
    });

// Remise à jour du token CSRF après chaque réponse
    $(document).on('ajaxSuccess', function (event, xhr, settings) {
        var response = xhr.responseJSON;
        if (response && response.csrf_token) {
            csrfHash = response.csrf_token; // Mise à jour du token
        }
    });
});

    const popoverTriggerList = document.querySelectorAll('[data-bs-toggle="popover"]')
    const popoverList = [...popoverTriggerList].map(popoverTriggerEl => new bootstrap.Popover(popoverTriggerEl))
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
    const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))


    
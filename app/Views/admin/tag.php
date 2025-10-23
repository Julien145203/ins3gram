<div class="row ">
    <!-- Colonne gauche : formulaire d'ajout de tag -->
    <div class="col-md-3">
        <div class="card">
            <!-- Formulaire CodeIgniter pour insérer un nouveau tag -->
            <?= form_open('admin/tag/insert') ?>
            <div class="card-header h4 bg-primary text-white">
                Ajouter un nouveau tag
            </div>
            <div class="card-body">
                <!-- Champ de saisie pour le nom du tag -->
                <div class="form-floating">
                    <input id="name" class="form-control" placeholder="Nom du tag" type="text" name="name" required>
                    <label for="name">Nom du tag</label>
                </div>
            </div>
            <div class="card-footer text-end">
                <!-- Bouton pour soumettre le formulaire -->
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Créer le tag
                </button>
            </div>
            <?= form_close() ?>
        </div>
    </div>

    <!-- Colonne droite : affichage de la liste des tags -->
    <div class="col-md-9">
        <div class="card">
            <div class="card-header h4 bg-primary text-white">
                Liste des tags
            </div>
            <div class="card-body">
                <!-- Table DataTables -->
                <table id="tagsTable" class="table table-sm table-hover table-bordered">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <!-- Corps du tableau (chargé dynamiquement par DataTables via AJAX) -->
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Bootstrap pour l'édition d’un tag -->
<div class="modal" id="modalTag" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- En-tête du modal -->
            <div class="modal-header">
                <h5 class="modal-title">Éditer le tag</h5>
                <!-- Bouton pour fermer le modal -->
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <!-- Corps du modal : champ d'édition du nom -->
            <div class="modal-body">
                <div class="form-floating">
                    <input type="text" class="form-control" id="modalNameInput" placeholder="Nom du tag" data-id="">
                    <label for="modalNameInput">Nom du tag</label>
                </div>
            </div>
            <!-- Pied du modal : boutons Annuler / Sauvegarder -->
            <div class="modal-footer">
                <button type="button" class="btn btn-danger text-white" data-bs-dismiss="modal">Annuler</button>
                <button onclick="saveTag()" type="button" class="btn btn-primary">Sauvegarder</button>
            </div>
        </div>
    </div>
</div>

<script>
    // Exécution du code une fois le DOM chargé
    $(document).ready(function() {
        // Base URL pour les requêtes (issue de CodeIgniter)
        var baseUrl = "<?= base_url(); ?>";

        // Initialisation du tableau DataTables
        var table = $('#tagsTable').DataTable({
            processing: true, // Indique que le traitement est en cours
            serverSide: true, // Active le mode serveur
            ajax: {
                url: '<?= base_url('datatable/searchdatatable') ?>', // Endpoint serveur
                type: 'POST',
                data: {
                    model: 'TagModel' // Spécifie le modèle utilisé côté serveur
                }
            },
            // Définition des colonnes du tableau
            columns: [
                { data: 'id' },   // Colonne ID
                { data: 'name' }, // Colonne Nom du tag
                {
                    data: null, // Colonne personnalisée (actions)
                    orderable: false, // Pas de tri sur cette colonne
                    render: function(data, type, row) {
                        // Boutons d'action (modifier / supprimer)
                        return `
                            <div class="" role="group">
                                <button onclick="showModal(${row.id},'${row.name}')" class="btn btn-sm btn-warning" title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button onclick="deleteTag(${row.id})" class="btn btn-sm btn-danger" title="Supprimer">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                        `;
                    }
                }
            ],
            order: [[0, 'desc']], // Tri par ID descendant par défaut
            pageLength: 10, // Nombre d'éléments par page
            language: {
                // Traduction française du DataTable
                url: baseUrl + 'js/datatable/datatable-2.1.4-fr-FR.json',
            }
        });

        // Fonction pour rafraîchir la table sans recharger la page
        window.refreshTable = function() {
            table.ajax.reload(null, false);
        };
    });

    // Initialisation du modal Bootstrap
    const myModal = new bootstrap.Modal('#modalTag');

    // Fonction appelée lors du clic sur le bouton Modifier
    function showModal(id, name) {
        // Remplit le champ du modal avec le nom du tag
        $('#modalNameInput').val(name);
        // Stocke l’ID du tag dans un attribut data
        $('#modalNameInput').data('id', id);
        // Affiche le modal
        myModal.show();
    }

    // Fonction pour sauvegarder la modification d’un tag
    function saveTag() {
        let name = $('#modalNameInput').val();
        let id = $('#modalNameInput').data('id');
        // Envoi AJAX vers le contrôleur CodeIgniter
        $.ajax({
            url: '<?= base_url('/admin/tag/update') ?>',
            type: 'POST',
            data: { name: name, id: id },
            success: function(response) {
                myModal.hide(); // Ferme le modal
                if (response.success) {
                    // Message de succès avec SweetAlert
                    Swal.fire({
                        title: 'Succès !',
                        text: response.message,
                        icon: 'success',
                        timer: 2000,
                        showConfirmButton: false
                    });
                    refreshTable(); // Recharge la table
                } else {
                    // Message d’erreur
                    Swal.fire({
                        title: 'Erreur !',
                        text: 'Une erreur est survenue',
                        icon: 'error'
                    });
                }
            }
        });
    }

    // Fonction pour supprimer un tag
    function deleteTag(id) {
        Swal.fire({
            title: `Êtes-vous sûr ?`,
            text: `Voulez-vous vraiment supprimer ce tag ?`,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#28a745",
            cancelButtonColor: "#6c757d",
            confirmButtonText: `Oui !`,
            cancelButtonText: "Annuler",
        }).then((result) => {
            if (result.isConfirmed) {
                // Requête AJAX pour suppression
                $.ajax({
                    url: '<?= base_url('/admin/tag/delete') ?>',
                    type: 'POST',
                    data: { id: id },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                title: 'Succès !',
                                text: response.message,
                                icon: 'success',
                                timer: 2000,
                                showConfirmButton: false
                            });
                            refreshTable();
                        } else {
                            Swal.fire({
                                title: 'Erreur !',
                                text: 'Une erreur est survenue',
                                icon: 'error'
                            });
                        }
                    }
                })
            }
        });
    }
</script>

<style>
    /* Centrage du texte dans le tableau */
    #tagsTable, #tagsTable th
    { text-align: center; }
</style>

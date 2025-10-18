<div class="row">
    <div class="col">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title">Liste des utilisateurs</h3>
                <a href="<?= base_url('/admin/user/new') ?>" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Nouvel Utilisateur
                </a>
            </div>
            <div class="card-body">
                <table id="usersTable" class="table table-sm table-bordered table-striped">
                    <thead>
                    <tr>
                        <th>Avatar</th>
                        <th>Prénom</th>
                        <th>Nom</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Permission</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <!-- Les données seront chargées via AJAX -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        var baseUrl = "<?= base_url(); ?>";
        var table = $('#usersTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '<?= base_url('datatable/searchdatatable') ?>',
                type: 'POST',
                data: {
                    model: 'UserModel'
                }
            },
            columns: [
                {
                    data: 'image',
                    render: function(data, type, row) {
                        if (data) {
                            return `<img src="${baseUrl}/${data}" style="height:40px; width:40px; object-fit:cover; border-radius:5px;">`;
                        }
                        return `<span class="text-muted">Aucune</span>`;
                    },
                    orderable: false,
                    className: 'text-center'
                },
                { data: 'first_name' },
                { data: 'last_name' },
                { data: 'username' },
                { data: 'email' },
                { data: 'permission_name' },
                {
                    data: 'status',
                    render: function(data, type, row) {
                        if (data === 'active' || row.deleted_at === null) {
                            return '<span class="badge bg-success">Actif</span>';
                        } else {
                            return '<span class="badge bg-danger">Inactif</span>';
                        }
                    }
                },
                {
                    data: null,
                    orderable: false,
                    render: function(data, type, row) {
                        const isActive = (row.status === 'active' || row.deleted_at === null);
                        const toggleButton = isActive
                            ? `<button class="btn btn-sm btn-danger" onclick="toggleUserStatus(${row.id}, 'deactivate')" title="Désactiver">
                                 <i class="fas fa-user-times"></i>
                               </button>`
                            : `<button class="btn btn-sm btn-success" onclick="toggleUserStatus(${row.id}, 'activate')" title="Activer">
                                 <i class="fas fa-user-check"></i>
                               </button>`;

                        return `
                            <div class="btn-group" role="group">
                                <a href="<?= base_url('/admin/user/') ?>${row.id}" class="btn btn-sm btn-warning" title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </a>
                                ${toggleButton}
                            </div>
                        `;
                    }
                }
            ],
            order: [[1, 'asc']], // Tri par prénom par défaut
            pageLength: 10,
            language: {
                url: baseUrl + 'js/datatable/datatable-2.1.4-fr-FR.json',
            }
        });

        // Fonction pour actualiser la table
        window.refreshTable = function() {
            table.ajax.reload(null, false); // false pour garder la pagination
        };
    });

    function toggleUserStatus(id, action) {
        const actionText = action === 'activate' ? 'activer' : 'désactiver';
        const actionColor = action === 'activate' ? '#28a745' : '#dc3545';

        Swal.fire({
            title: `Êtes-vous sûr ?`,
            text: `Voulez-vous vraiment ${actionText} cet utilisateur ?`,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: actionColor,
            cancelButtonColor: "#6c757d",
            confirmButtonText: `Oui, ${actionText} !`,
            cancelButtonText: "Annuler",
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "<?= base_url('/admin/user/switch-active'); ?>",
                    type: "POST",
                    data: { 'id_user': id },
                    success: function (response) {
                        console.log(response);

                        if (response.success) {
                            Swal.fire({
                                title: 'Succès !',
                                text: response.message,
                                icon: 'success',
                                timer: 4000,
                                showConfirmButton: false
                            });

                            // Actualiser la table
                            refreshTable();
                        } else {
                            Swal.fire({
                                title: 'Erreur !',
                                text: response.message || 'Une erreur est survenue',
                                icon: 'error'
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Erreur AJAX:', error);
                        Swal.fire({
                            title: 'Erreur !',
                            text: 'Erreur de communication avec le serveur',
                            icon: 'error'
                        });
                    }
                });
            }
        });
    }
</script>
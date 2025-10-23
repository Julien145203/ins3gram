<div class="row">
    <div class="col">
        <div class="card">
            <!-- Entête de la card avec titre et bouton pour ajouter un nouvel utilisateur -->
            <div class="card-header d-flex justify-content-between align-items-center bg-primary text-white">
                <h3 class="card-title">Liste des utilisateurs</h3>
                <a href="<?= base_url('/admin/user/new') ?>" class="btn btn-sm btn-light">
                    <i class="fas fa-plus"></i> Nouvel Utilisateur
                </a>
            </div>

            <!-- Corps de la card contenant le tableau des utilisateurs -->
            <div class="card-body">
                <table id="usersTable" class="table table-sm table-bordered table-striped">
                    <thead>
                    <tr>
                        <th>ID</th>
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
                    <!-- Les données seront chargées via AJAX côté DataTable -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        var baseUrl = "<?= base_url(); ?>"; // URL de base pour les requêtes

        // Initialisation du DataTable pour les utilisateurs
        var table = $('#usersTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '<?= base_url('datatable/searchdatatable') ?>',
                type: 'POST',
                data: { model: 'UserModel' }
            },
            columns: [
                {data: 'id'},
                {
                    data: null,
                    render: function(data, type, row) {
                        const avatar = row.image ?? 'assets/img/avatars/1.jpg';
                        return `<img src="<?= base_url(); ?>/${avatar}" style="height:40px; width:40px; object-fit:cover; border-radius:5px;">`;
                    },
                    orderable: false,
                },
                { data: 'first_name' },
                { data: 'last_name' },
                { data: 'username' },
                { data: 'email' },
                { data: 'permission_name' },
                {
                    data: 'status',
                    render: function(data, type, row) {
                        return (data === 'active' || row.deleted_at === null)
                            ? '<span class="badge bg-success">Actif</span>'
                            : '<span class="badge bg-danger">Inactif</span>';
                    }
                },
                {
                    data: null,
                    orderable: false,
                    render: function(data, type, row) {
                        const isActive = (row.status === 'active' || row.deleted_at === null);
                        const toggleButton = isActive
                            ? `<button class="btn btn-sm btn-danger" onclick="toggleUserStatus(${row.id}, 'deactivate')" title="Désactiver"><i class="fas fa-user-times"></i></button>`
                            : `<button class="btn btn-sm btn-success" onclick="toggleUserStatus(${row.id}, 'activate')" title="Activer"><i class="fas fa-user-check"></i></button>`;
                        return `
                        <div class="" role="group">
                            <a href="<?= base_url('/admin/user/') ?>${row.id}" class="btn btn-sm btn-warning" title="Modifier">
                                <i class="fas fa-edit"></i>
                            </a>
                            ${toggleButton}
                            <button class="btn btn-sm btn-primary" onclick="deleteUser(${row.id})" title="Supprimer">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </div>
                    `;
                    }
                }
            ],
            order: [[1, 'asc']],
            pageLength: 10,
            language: { url: baseUrl + 'js/datatable/datatable-2.1.4-fr-FR.json' }
        });

        // Rafraîchir la table sans réinitialiser la pagination
        window.refreshTable = function() {
            table.ajax.reload(null, false);
        };
    });

    // Fonctions globales liées aux utilisateurs
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
                        if (response.success) {
                            Swal.fire({ title: 'Succès !', text: response.message, icon: 'success', timer: 4000, showConfirmButton: false });
                            refreshTable();
                        } else {
                            Swal.fire({ title: 'Erreur !', text: response.message || 'Une erreur est survenue', icon: 'error' });
                        }
                    },
                });
            }
        });
    }
    // Fonction pour supprimer un utilisateur
    function deleteUser(id) {
        Swal.fire({
            title: `Êtes-vous sûr ?`,
            text: `Voulez-vous vraiment supprimer cet utilisateur ?`,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#dc3545",
            cancelButtonColor: "#6c757d",
            confirmButtonText: `Oui, supprimer !`,
            cancelButtonText: "Annuler",
        }).then((result) => {
            if(result.isConfirmed){
                $.post('<?= base_url('/admin/user/delete') ?>',{id},function(response){
                    if(response.success){
                        Swal.fire({ title:'Succès !', text:response.message, icon:'success', timer:2000, showConfirmButton:false });
                        refreshTable();
                    } else {
                        Swal.fire({ title:'Erreur !', text:response.message || 'Une erreur est survenue', icon:'error' });
                    }
                });
            }
        });
    }

</script>

<!-- Style pour centrer le texte dans le tableau -->
<style>
    #usersTable, #usersTable th {
        text-align: center;
    }
</style>

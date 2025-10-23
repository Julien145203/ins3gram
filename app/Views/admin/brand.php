<div class="row">
    <!-- Colonne gauche : Carte total + Formulaire création -->
    <div class="col-md-3">
        <!-- #new Card Total Marques -->
        <div class="card text-white bg-primary shadow-sm mb-3">
            <div class="card-body d-flex justify-content-evenly">
                <!-- Icône à gauche -->
                <div class="display-4"><i class="fas fa-tags"></i></div>
                <!-- Texte à droite : titre + nombre total -->
                <div class="text-center">
                    <h5 class="card-title mb-1">Total Marques</h5>
                    <h2 class="card-text mb-0"><?= esc($totalBrands) ?></h2>
                </div>
            </div>
        </div>

        <!-- Formulaire création marque -->
        <div class="card shadow-sm border-1">
            <?= form_open_multipart('admin/brand/insert') ?>
            <div class="card-header bg-primary text-white h4">
                Créer une marque
            </div>
            <div class="card-body">
                <!-- Champ texte pour le nom -->
                <div class="form-floating mb-3">
                    <input id="name" class="form-control" placeholder="Nom de la marque" type="text" name="name" required>
                    <label for="name">Nom de la marque</label>
                </div>
                <!-- #new Champ fichier pour l'image -->
                <div class="mb-3">
                    <label for="image" class="form-label">Image de la marque</label>
                    <input type="file" class="form-control" name="image" id="image" accept="image/*">
                    <!-- #new Preview image -->
                    <img id="previewImage" src="#" alt="Prévisualisation" class="img-fluid mt-2 d-none" style="max-height:120px;border-radius:8px;">
                </div>
            </div>
            <div class="card-footer text-end">
                <button type="submit" class="btn btn-primary"><i class="fas fa-plus"></i> Créer</button>
            </div>
            <?= form_close() ?>
        </div>
    </div>

    <!-- Colonne droite : Tableau des marques D1-->
    <div class="col-md-9">
        <div class="card shadow-sm border-1">
            <div class="card-header bg-primary text-white h4">
                Liste des marques
            </div>
            <div class="card-body">
                <table id="brandTable" class="table table-hover table-striped align-middle">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th style="width:80px;">Image</th> <!--#new -->
                        <th>Nom</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody></tbody> <!-- Rempli via DataTable -->
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal édition //fade-->
<div class="modal " id="modalBrand" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title">Éditer la marque</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
                <!-- Champ texte modal pour le nom -->
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="modalNameInput" placeholder="Nom de la marque" data-id="">
                    <label for="modalNameInput">Nom de la marque</label>
                </div>
                <!-- Champ fichier modal pour changer l'image #new -->
                <div class="mb-3">
                    <label for="modalImageInput" class="form-label">Changer l'image</label>
                    <input type="file" class="form-control" id="modalImageInput" accept="image/*">
                    <!-- Preview image modal -->
                    <img id="modalPreviewImage" src="#" alt="Prévisualisation" class="img-fluid mt-2" style="max-height:120px;border-radius:8px;">
                </div>
            </div>
            <!-- Preview #D1 -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button id="saveBrandBtn" type="button" class="btn btn-primary">
                    <span class="spinner-border spinner-border-sm d-none" role="status" id="loaderEdit"></span>Sauvegarder
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        var baseUrl = "<?= base_url(); ?>"; // const plus adaptée, portée bloc

        // ----- Prévisualisation image création #new -----
        $("#image").change(function(){
            let file = this.files[0];
            if(file){
                let reader = new FileReader();
                reader.onload = e => $("#previewImage").attr("src", e.target.result).removeClass("d-none");
                reader.readAsDataURL(file);
            }
        });

        // ----- Prévisualisation image modal #new -----
        $("#modalImageInput").change(function(){
            let file = this.files[0];
            if(file){
                let reader = new FileReader();
                reader.onload = e => $("#modalPreviewImage").attr("src", e.target.result).show();
                reader.readAsDataURL(file);
            }
        });

        // ----- DataTable -----
        var table = $('#brandTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '<?= base_url('datatable/searchdatatable') ?>',
                type: 'POST',
                data: { model: 'BrandModel' } // Indique le modèle côté serveur
            },
            columns: [
                {data : 'id' },
                //Affichage image ok
                {
                    data: null,
                    orderable: false,
                    render: function(data, type, row) {
                        if (row.image) {
                            return `<img src="${baseUrl}${row.image}" style="height:40px;border-radius:5px;">`;
                        } else {
                            return `<span class="text-muted">Pas d'image</span>`;
                        }
                    }
                },
                // Nom
                { data: 'name' },
                // Boutons actions : modifier et supprimer + encodeURIComponent pour éviter problèmes d'apostrophes
                {
                    data: null,
                    orderable: false,
                    render: function(data, type, row) {
                        // encodeURIComponent supprimé pour compatibilité modal
                        return `
                    <div class="" role="group">
                        <button class="btn btn-sm btn-warning btn-edit"
                                data-id="${row.id}"
                                data-name="${row.name}"
                                data-image="${row.image || ''}"
                                title="Modifier">
                            <i class="fas fa-edit"></i>
                        </button>

                        <button class="btn btn-sm btn-danger btn-delete "
                                data-id="${row.id}"
                                title="Supprimer">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </div>`;
                    }
                }
            ],
            order: [[0,'desc']],
            pageLength: 10,
            language: { url: baseUrl + 'js/datatable/datatable-2.1.4-fr-FR.json' }
        });

        // ----- Rafraîchir la table -----
        window.refreshTable = function() {
            table.ajax.reload(null,false); // false pour garder la pagination
        };

        // ----- Ouvrir modal avec data-attributes -----
        const myModal = new bootstrap.Modal('#modalBrand'); //var?
        $(document).on('click', '.btn-edit', function() {
            const id = $(this).data('id');
            const name = $(this).data('name');
            const image = $(this).data('image');

            $('#modalNameInput').val(name).data('id', id);// Remplit le nom +  Stocke l'ID
            $('#modalImageInput').val('');
            if(image) {// Avec et sans prévisualisation image
                $('#modalPreviewImage').attr('src', baseUrl+'/'+image).show();
            } else {
                $('#modalPreviewImage').attr('src', '#').hide();
            }
            myModal.show();
        });

        // ----- Sauvegarder modal -----
        $('#saveBrandBtn').on('click', function() {
            const id = $('#modalNameInput').data('id');
            const name = $('#modalNameInput').val();
            const file = $('#modalImageInput')[0].files[0];

            const formData = new FormData();          // Crée conteneur - Peut d'option FileReader/ <iframe> cacher avec form natif
            formData.append('id', id);                // Ajoute ID
            formData.append('name', name);            // Ajoute nom
            if (file) formData.append('image', file); // Ajoute image

            $('#loaderEdit').removeClass('d-none'); // Affiche loader

            $.ajax({
                url: '<?= base_url('/admin/brand/update') ?>',
                type: 'POST',
                data: formData, // Doit passer toutes les infos en FormData
                processData:false, // Necessaire pour FormData
                contentType:false, // Necessaire pour FormData
                success: function(response){
                    $('#loaderEdit').addClass('d-none'); // Cache loader
                    myModal.hide();
                    if(response.success){
                        Swal.fire({
                            title:'Succès !',
                            text:response.message,
                            icon:'success',
                            timer:2000,
                            showConfirmButton:false
                        });
                        // Actualiser la table
                        refreshTable();
                    } else {
                        console.log(response.message); //let msg = typeof response.message === 'object' ? Object.values(response.message).flat().join("\n") : response.message; Pour une gestion erreur plus precise
                        Swal.fire({
                            title:'Erreur !',
                            text:'Une erreur est survenue', // text:msg||'Une erreur est survenue', affiche plus d'information dans la gestion des erreurs
                            icon:'error'
                        });
                    }
                },
                error: function(){
                    $('#loaderEdit').addClass('d-none');
                    Swal.fire({ title:'Erreur !', text:'Problème réseau', icon:'error' });
                }
            });
        });

        // ----- Supprimer marque -----
        $(document).on('click', '.btn-delete', function() {
            const id = $(this).data('id');
            Swal.fire({
                title: 'Êtes-vous sûr ?',
                text: 'Voulez-vous vraiment supprimer cette marque ?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Oui !',
                cancelButtonText: 'Annuler',
            }).then(function(result) {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '<?= base_url('/admin/brand/delete') ?>',
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
                                // Actualiser la table
                                refreshTable();
                            } else {
                                console.log(response.message);
                                Swal.fire({
                                    title: 'Erreur !',
                                    text: response.message || 'Une erreur est survenue',
                                    icon: 'error'
                                });
                            }
                        },
                        error: function() {
                            Swal.fire({ title:'Erreur !',
                                text:'Problème réseau',
                                icon:'error'
                            });
                        }
                    });
                }
            });
        });

    });
</script>
<style>
    #brandTable, #brandTable th
    {text-align: center}
</style>
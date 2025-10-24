<div class="row">
    <div class="col">
        <div class="card">
            <div class="card-header">
                <?php if(isset($user)) : ?>
                    <h2>Modification de <?= esc($user->username); ?></h2>
                <?php else : ?>
                    <h2>Création d'un utilisateur</h2>
                <?php endif;?>
            </div>

            <?php
            if(isset($user)):
                echo form_open_multipart('admin/user/update', ['class' => 'needs-validation', 'novalidate' => true]); ?>
                <input type="hidden" name="id" value="<?= $user->id ?>">
            <?php
            else:
                echo form_open_multipart('admin/user/insert', ['class' => 'needs-validation', 'novalidate' => true]);
            endif;
            ?>

            <div class="card-body">
                <!-- Avatar + upload -->
                <div class="row mb-3">
                    <div class="col-12 text-center">
                        <div class="d-inline-block position-relative" id="avatar">
                            <?php if(isset($user) && $user->hasAvatar()): ?>
                                <div id="avatar-hover" class="rounded-circle h-100 w-100 position-absolute"
                                     style="background-color: rgba(0, 0, 0, 0.2); display:none; top:0; left:0;">
                                    <div class="d-flex justify-content-center align-items-center h-100 w-100">
                                        <i class="fas fa-trash-can fa-2xl text-danger"></i>
                                    </div>
                                </div>
                                <img src="<?= $user->getAvatarUrl() ?>"
                                     <!--TODO: revoir taille image-->
                                     alt="Avatar"
                                     class="rounded-circle img-thumbnail"
                                     style="width: 150px; height: 150px; object-fit: cover;">
                                <p class="text-muted small mt-2">Avatar actuel</p>
                            <?php else: ?>
                                <img src="<?= base_url('assets/img/avatars/1.jpg') ?>"
                                     alt="Avatar"
                                     class="rounded-circle img-thumbnail"
                                     style="width: 150px; height: 150px; object-fit: cover;">
                                <p class="text-muted small mt-2">Aucun avatar <br> (image par défaut)</p>
                            <?php endif; ?>
                        </div>

                        <!-- Champ pour uploader un nouvel avatar -->
                        <div class="mt-3 w-50 mx-auto">
                            <input type="file" name="avatar" class="form-control">
                        </div>
                    </div>
                </div>

                <!-- Champs utilisateur -->
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text"
                                   name="first_name"
                                   id="first_name"
                                   class="form-control"
                                   placeholder="Prénom"
                                   value="<?= isset($user) ? esc($user->first_name) : set_value('first_name') ?>">
                            <label for="first_name">Prénom</label>
                            <div class="invalid-feedback">
                                <?= validation_show_error('first_name') ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text"
                                   name="last_name"
                                   id="last_name"
                                   class="form-control"
                                   placeholder="Nom"
                                   value="<?= isset($user) ? esc($user->last_name) : set_value('last_name') ?>">
                            <label for="last_name">Nom</label>
                            <div class="invalid-feedback">
                                <?= validation_show_error('last_name') ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-floating">
                            <input type="text"
                                   name="username"
                                   id="username"
                                   class="form-control"
                                   placeholder="Nom d'utilisateur"
                                   value="<?= isset($user) ? esc($user->username) : set_value('username') ?>"
                                   required>
                            <label for="username">Nom d'utilisateur <span class="text-danger">*</span></label>
                            <div class="invalid-feedback">
                                <?= validation_show_error('username') ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-floating">
                            <input type="email"
                                   name="email"
                                   id="email"
                                   class="form-control"
                                   placeholder="Adresse email"
                                   value="<?= isset($user) ? esc($user->email) : set_value('email') ?>"
                                   required>
                            <label for="email">Adresse email <span class="text-danger">*</span></label>
                            <div class="invalid-feedback">
                                <?= validation_show_error('email') ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-floating">
                            <input type="password"
                                   name="password"
                                   id="password"
                                   class="form-control"
                                   minlength="8"
                                   placeholder="<?= isset($user) ? 'Nouveau mot de passe (laisser vide pour conserver l\'actuel)' : 'Mot de passe' ?>"
                                <?= !isset($user) ? 'required' : '' ?>>
                            <label for="password">
                                <?php if(isset($user)) : ?>
                                    Nouveau mot de passe <small class="text-muted">(laisser vide pour conserver l'actuel)</small>
                                <?php else : ?>
                                    Mot de passe <span class="text-danger">*</span>
                                <?php endif; ?>
                            </label>
                            <div class="invalid-feedback">
                                <?= validation_show_error('password') ?>
                            </div>
                            <div class="form-text">Le mot de passe doit contenir au moins 8 caractères.</div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="date"
                                   name="birthdate"
                                   id="birthdate"
                                   class="form-control"
                                   placeholder="Date de naissance"
                                   value="<?= isset($user) && $user->birthdate ? date('Y-m-d', strtotime($user->birthdate)) : set_value('birthdate') ?>"
                                   required>
                            <label for="birthdate">Date de naissance <span class="text-danger">*</span></label>
                            <div class="invalid-feedback">
                                <?= validation_show_error('birthdate') ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-floating">
                            <select name="id_permission" id="id_permission" class="form-select" required>
                                <option disabled>Choisir un rôle...</option>
                                <?php if(isset($permissions) && is_array($permissions)) : ?>
                                    <?php foreach($permissions as $permission) : ?>
                                        <option value="<?= $permission['id'] ?>"
                                            <?= (isset($user) && $user->id_permission == $permission['id']) || set_value('id_permission') == $permission['id'] ? 'selected' : '' ?>>
                                            <?= esc($permission['name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                            <label for="id_permission">Rôle <span class="text-danger">*</span></label>
                            <div class="invalid-feedback">
                                <?= validation_show_error('id_permission') ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-footer d-flex justify-content-between">
                <a href="<?= base_url('admin/user'); ?>" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i>Retour
                </a>
                <div>
                    <?php if(isset($user)) : ?>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>Mettre à jour
                        </button>
                    <?php else : ?>
                        <button type="reset" class="btn btn-outline-secondary me-2">
                            <i class="fas fa-undo me-1"></i>Réinitialiser
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-plus me-1"></i>Créer l'utilisateur
                        </button>
                    <?php endif; ?>
                </div>
            </div>

            <?php echo form_close(); ?>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        var base_url = '<?= base_url() ?>';

        $('#avatar').on('mouseenter', function() {
            $('#avatar-hover').show();
        });
        $('#avatar').on('mouseleave', function() {
            $('#avatar-hover').hide();
        });

        $('#avatar-hover .fa-trash-can').on('click', function(){
            Swal.fire({
                title: "Supprimer l'avatar ?",
                text: "Il n'y aura pas de retour possible !",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "var(--cui-primary)",
                cancelButtonColor: "var(--cui-danger)",
                confirmButtonText: "Oui, supprime !",
                cancelButtonText: "Annuler"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url : base_url + "admin/user/delete-avatar",
                        type : "POST",
                        data : {
                            'id_user' : '<?= isset($user) ? $user->id : '' ?>'
                        },
                        success: function(response) {
                            if(response.success) {
                                Swal.fire({
                                    icon : 'success',
                                    title : 'Avatar supprimé !'
                                });
                                $('#avatar').html('<p class="text-muted small">Aucun avatar</p>');
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Erreur',
                                    text: response.message
                                });
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error(error);
                        }
                    });
                }
            });
        });

        // Prévisualisation de l'image avant envoi
        $('input[name="avatar"]').on('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const imgHtml = `
                <div class="d-inline-block position-relative">
                    <img src="${e.target.result}"
                         alt="Avatar preview"
                         class="rounded-circle img-thumbnail"
                         style="width:150px;height:150px;object-fit:cover;">
                    <p class="text-muted small mt-2">Votre avatar actuel <br>(non encore enregistré)</p>
                </div>`;
                    $('#avatar').html(imgHtml);
                };
                reader.readAsDataURL(file);
            }
        });
    });
</script>

<style>
    #avatar {
        width: 150px;
        height: 150px;
        margin: 0 auto;
        position: relative;
    }
    #avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 50%;
    }
    #avatar-hover {
        cursor: pointer;
        border-radius: 50%;
    }
    .fa-trash-can {
        cursor: pointer;
    }
</style>

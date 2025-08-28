<div class="row">
    <div class="col">
        <div class="card">
            <div class="card-header">
                <?php if(isset($user)) : ?>
                    <!-- Si l'utilisateur existe déjà : on affiche "Modification" -->
                    Modification de <?= esc($user->username); ?>
                <?php else : ?>
                    <!-- Sinon : on affiche "Création d'un utilisateur" -->
                    Création d'un utilisateur
                <?php endif;?>
            </div>
            <?php
            // Ouverture du formulaire selon le cas : update ou create
            if(isset($user)):
                echo form_open('admin/user/update', ['class' => 'needs-validation', 'novalidate' => true]); ?>
                <!-- Champ caché pour stocker l'ID de l'utilisateur lors de la modification -->
                <input type="hidden" name="id" value="<?= $user->id ?>">
            <?php
            else:
                echo form_open('admin/user/insert', ['class' => 'needs-validation', 'novalidate' => true]);
            endif;
            ?>
            <div class="card-body">
                <div class="row g-3">
                    <!-- Prénom et Nom sur la même ligne -->
                    <div class="col-md-6">
                        <div class="form-floating">
                            <!-- Champ pour le prénom -->
                            <input type="text"
                                   name="first_name"
                                   id="first_name"
                                   class="form-control"
                                   placeholder="Prénom"
                                   value="<?= isset($user) ? esc($user->first_name) : set_value('first_name') ?>">
                            <label for="first_name">Prénom</label>
                            <!-- Affichage d'une éventuelle erreur de validation -->
                            <div class="invalid-feedback">
                                <?= validation_show_error('first_name') ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating">
                            <!-- Champ pour le nom -->
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

                    <!-- Nom d'utilisateur -->
                    <div class="col-12">
                        <div class="form-floating">
                            <!-- Champ obligatoire pour le nom d'utilisateur -->
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

                    <!-- Email -->
                    <div class="col-12">
                        <div class="form-floating">
                            <!-- Champ obligatoire pour l'email -->
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

                    <!-- Mot de passe -->
                    <div class="col-12">
                        <div class="form-floating">
                            <!-- Champ mot de passe : obligatoire si création, optionnel si modification -->
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
                            <div class="form-text">
                                <!-- Indication pour l'utilisateur -->
                                Le mot de passe doit contenir au moins 8 caractères.
                            </div>
                        </div>
                    </div>

                    <!-- Date de naissance et Permissions sur la même ligne -->
                    <div class="col-md-6">
                        <div class="form-floating">
                            <!-- Champ obligatoire pour la date de naissance -->
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
                            <!-- Liste déroulante des rôles disponibles -->
                            <select name="id_permission" id="id_permission" class="form-select" required>
                                <option disabled >Choisir un rôle...</option>
                                <?php if(isset($permissions) && is_array($permissions)) : ?>
                                    <?php foreach($permissions as $permission) : ?>
                                        <!-- Si l'utilisateur a déjà un rôle, on le sélectionne -->
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
                <!-- Bouton de retour -->
                <a href="<?= base_url('admin/user'); ?>" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i>Retour
                </a>
                <div>
                    <?php if(isset($user)) : ?>
                        <!-- Si modification : bouton "Mettre à jour" -->
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>Mettre à jour
                        </button>
                    <?php else : ?>
                        <!-- Si création : bouton pour réinitialiser + bouton pour créer -->
                        <button type="reset" class="btn btn-outline-secondary me-2">
                            <i class="fas fa-undo me-1"></i>Réinitialiser
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-plus me-1"></i>Créer l'utilisateur
                        </button>
                    <?php endif; ?>
                </div>
            </div>
            <?php
            // Fermeture du formulaire
            echo form_close();
            ?>
        </div>
    </div>
</div>

<script>
    // Ici, on ouvre une "fonction auto-exécutée".
    // Cela veut dire que ce bloc de code va s'exécuter tout seul dès que la page est chargée.
    (function() {
        'use strict';
        // "use strict" demande à JavaScript d’être plus strict.
        // Par exemple, il interdit certaines mauvaises pratiques de code.
        // C’est une bonne habitude pour éviter des erreurs.

        // On sélectionne TOUS les formulaires qui ont la classe "needs-validation"
        // (c’est une classe Bootstrap utilisée pour la mise en forme et la validation).
        var forms = document.querySelectorAll('.needs-validation');

        // Comme "forms" contient une liste (plusieurs formulaires),
        // on transforme cette liste en tableau avec "Array.prototype.slice.call(forms)".
        // Ça permet de pouvoir faire "forEach" dessus (boucle).
        Array.prototype.slice.call(forms).forEach(function(form) {

            // Pour chaque formulaire trouvé, on ajoute un "écouteur d’événement".
            // Ici, on écoute quand le formulaire veut être envoyé ("submit").
            form.addEventListener('submit', function(event) {

                // Si le formulaire n’est PAS valide...
                // (par exemple : un champ "required" est vide)
                if (!form.checkValidity()) {

                    // ... alors on empêche l’envoi du formulaire au serveur
                    event.preventDefault();

                    // ... et on arrête aussi d’autres actions liées à l’événement
                    event.stopPropagation();
                }

                // Dans tous les cas, on ajoute la classe "was-validated" au formulaire.
                // C’est une classe CSS de Bootstrap qui va afficher les messages d’erreurs
                // et mettre en rouge les champs invalides.
                form.classList.add('was-validated');

            }, false); // "false" signifie qu’on utilise le mode "bulle" par défaut pour l’événement
        });
    })();
</script>
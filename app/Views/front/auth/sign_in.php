<div class=" d-flex flex-row align-items-center min-vh-100">
    <div class="col">
        <div class="row flex-column align-content-center">
            <div class="col-6">
                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger">
                        <?= session()->getFlashdata('error') ?>
                    </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success">
                        <?= session()->getFlashdata('success') ?>
                    </div>
                <?php endif; ?>


                <div class="card">
                    <div class="card-header chewy text-center h1">
                        Connexion
                    </div>
                    <?= form_open('auth/login') ?>
                    <!--<input type="hidden" name="--><?php //= csrf_token_name(); ?><!--" value="--><?php //= csrf_hash(); ?><!--">-->
                    <div class="card-body">
                        <div class="row flex-column align-content-center">
                            <div class="col-6">
                                <img src="<?= base_url('assets/img/logo-full.png') ?>" alt="Ins3gram" class="w-100">
                            </div>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="email" class="form-control" id="floatingInput" placeholder="name@example.com" name="email" required>
                            <label for="floatingInput">Adresse Email</label>
                        </div>
                        <div class="form-floating">
                            <input type="password" class="form-control" id="floatingPassword" placeholder="Password" name="password" required>
                            <label for="floatingPassword">Mot de Passe</label>
                        </div>
                    </div>
                    <div class="card-footer text-end">
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Se connecter</button>
                        </div>
                    </div>
                    <?= form_close() ?>
                </div>
            </div>
        </div>
    </div>
</div>

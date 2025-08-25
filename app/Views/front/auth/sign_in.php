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
            <div class="card-header">
                Se connecter
            </div>
            <form action="<?= base_url('auth/login'); ?>" method="POST">
                <div class="card-body">
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
                        <button type="submit" class="btn btn-primary">Se connecter</button>
                    </div>
            </form>
        </div>
    </div>
</div>

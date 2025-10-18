<div class="row">
    <div class="col-12 text-center">
        <h1>Contactez-nous</h1>
    </div>
    <div class="col-md-6 order-2 order-md-1 mb-3">
        <div class="card h-100">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2763.615820317104!2d-1.1038924231412013!3d46.15839577109405!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x48014d1ac0691173%3A0x2689e822080ddc4e!2sCipecma%20P%C3%A9rigny!5e0!3m2!1sfr!2sfr!4v1760088309430!5m2!1sfr!2sfr" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
    </div>
    <div class="col-md-6 order-1 order-md-2 mb-3">
        <div class="card">
            <div class="card-body">
                <?php
                if (isset($success)) : ?>
                    <div class="alert alert-success">
                        <?= $success ?>
                    </div>
                <?php endif;?>
                <?= form_open(base_url('/contactez-nous/send')); ?>

                <div class="form-floating mb-3">
                    <input type="text"
                           name="subject"
                           id="subject"
                           class="form-control <?= isset($errors) && $errors->hasError('subject') ? 'is-invalid' : '' ?>"
                           placeholder="Sujet"
                           value="<?= (isset($data) && isset($data['subject'])) ? esc($data['subject']) : '' ?>">
                    <label for="subject">Objet</label>
                    <div class="invalid-feedback">
                        <?= isset($errors) ? $errors->getError('subject') : '' ?>
                    </div>
                </div>

                <div class="form-floating mb-3">
                    <input type="email"
                           name="email"
                           id="email"
                           class="form-control <?= isset($errors) && $errors->hasError('email') ? 'is-invalid' : '' ?>"
                           placeholder="Email"
                           value="<?= (isset($data) && isset($data['email'])) ? esc($data['email']) : '' ?>">
                    <label for="email">Votre adresse mail</label>
                    <div class="invalid-feedback">
                        <?= isset($errors) ? $errors->getError('email') : '' ?>
                    </div>
                </div>

                <div class="form-floating mb-3">
    <textarea name="message"
              id="message"
              class="form-control <?= isset($errors) && $errors->hasError('message') ? 'is-invalid' : '' ?>"
              placeholder="Message"
              style="height: 10rem"><?= (isset($data) && isset($data['message'])) ? esc($data['message']) : '' ?></textarea>
                    <label for="message">Votre message</label>
                    <div class="invalid-feedback">
                        <?= isset($errors) ? $errors->getError('message') : '' ?>
                    </div>
                </div>

                <div class="form-check form-switch mb-3">
                    <input class="form-check-input <?= isset($errors) && $errors->hasError('rgpd') ? 'is-invalid' : '' ?>"
                           type="checkbox"
                           name="rgpd"
                           role="switch"
                           id="rgpd"
                        <?= (isset($data['rgpd']) && isset($data['rgpd'])) ? 'checked' : '' ?>
                    >
                    <label class="form-check-label" for="rgpd">
                        J'accepte que mon email soit utilisé pour me répondre
                    </label>
                    <div class="invalid-feedback">
                        <?= isset($errors) ? $errors->getError('rgpd') : '' ?>
                    </div>
                </div>

                <input type="submit" value="Envoyer" class="btn btn-primary">

                <?= form_close(); ?>
            </div>
        </div>

    </div>
</div>
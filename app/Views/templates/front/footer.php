</div>
</body>
<footer class="container-fluid ">
    <row class="row mt-3 p-3 bg-primary text-white">
        <div class="row justify-content-md-center text-center">
            <div class="col">
                16 Allée des Martinets, 17370 Saint-Trojan-les-Bains
                <br>
                Lotissement Les Camelias, 17480 Le Château-d'Oléron
            </div>
            <div class="col">
                Boinot-Geay Location
                <div itemscope itemtype="">
                    <p itemprop="name">
                        <b>Location Meublés De Tourisme | Oléron</b>
                    </p>
                </div>

            </div>
            <div class="col">
                <a href="<?= base_url("/contact")?>" class="text-white link-underline link-underline-opacity-0">Contact</a> <br>
                <span itemprop="telephone">
                        <a href="tel:+33661844381" class="text-white link-underline link-underline-opacity-0">06 61 84 43 81</a>
                    </span>
            </div>
        </div>
        <div class="row justify-content-md-center text-center mt-3">
            <div class="col col-lg-3">
                <a class="link-offset-2 link-underline link-underline-opacity-0 text-secondary" href="<?= base_url($lang == 'fr' ? '/mentions-legales' : '/en/legal-notice'); ?>"><?= lang('GLobal.legal-notice'); ?></a>
            </div>
            <div class="col col-lg-3">
                <a class="link-offset-2 link-underline link-underline-opacity-0 text-secondary" href="<?= base_url($lang == 'fr' ? '/politique-de-confidentialite' : '/en/privacy-policy'); ?>"><?= lang('GLobal.privacy-policy'); ?></a>
            </div>
            <div class="col col-lg-3">
                <a class="link-offset-2 link-underline link-underline-opacity-0 text-secondary" href="<?= base_url($lang == 'fr' ? '/conditions-generales-de-location ' : '/en/rental-terms-and-conditions'); ?>"><?= lang('GLobal.rental-terms-and-conditions'); ?></a>
        </div>
    </row>
</footer>
</html>
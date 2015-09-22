<?php if (Language::getEnabled()) { ?>
    <script>
        $(document).ready(function() {
            $('[data-toggle="tooltip"]').tooltip({
                html: true
            });
        });
    </script>
    <div class="languages-list">
        <?php foreach (Language::getEnabled() as $lang) { ?>
            <a href="javascript: void(0)" <?=(Language::getActive() == $lang) ? 'class="active"' : '' ;?> data-toggle="tooltip" data-placement="top" title="<?=strtoupper($lang);?><?=(Language::getActive() == $lang) ? ' - ' . __('current') : '' ;?>" onclick="setLanguage('<?=$lang?>'); return false;"><img src="<?=config('app.base_url') . '/assets/img/flags/' . strtoupper($lang) . '.png';?>" /></a>
        <?php } ?>
    </div>
<?php } ?>

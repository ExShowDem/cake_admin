<?php
    if (isset($lang_choice_visible) && (!$lang_choice_visible)) {
        //do nothing
    } else {
?>
        <div class="menu">
            <?php
                foreach($display_available_lang as $lang) {
            ?>
                <div><a class="zho btn-change-language" data-lang="<?= $lang ?>"><?= __($lang.'_sign') ?></a></div>
            <?php
                }
            ?>
            <?php
                if (isset($is_logout_visible) && ($is_logout_visible)) {
            ?>
                    <div>
                        <?= $this->Html->link(__('logout'), 
                                array('controller' => 'frontpage', 'action' => 'do_logout'), array('class' => 'text-med narrow line-initial white', 'escape' => false)) ?>
                    </div>
            <?php
                }
            ?>
        </div>
<?php
    } 
?>
<?php
    if (isset($is_header_visible) && ($is_header_visible)) {
?>
    <div class="header">
        this is header
    </div>
<?php
    } 
?>
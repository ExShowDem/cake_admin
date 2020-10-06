<div class="div-footer-container">
    <div class="div-line-footer"></div>
    <div class="div-logo center">
        <img src="<?= $webroot ?>general/logo.png">
    </div>
    <div class="div-term-policy center-self">
        <div class="content smallest">
            <?= $this->Html->link(__('terms_conditions'), 
                            array('controller' => 'termspage', 'action' => 'index'), array('class' => 'black', 'escape' => false)); ?>
        </div>
        <div class="content smallest">
            <?= $this->Html->link(__('cust_data_policy'), 
                            array('controller' => 'termspage', 'action' => 'privacy'), array('class' => 'black', 'escape' => false)); ?>            
        </div>
    </div>
    <div class="div-copyright content super-small white-grey center">
        © 2020 Sun Hung Kai Properties Limited. All rights reserved.
    </div>
</div>
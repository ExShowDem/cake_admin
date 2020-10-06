<aside class="left-side sidebar-offcanvas">
	<section class="sidebar">
        <ul class="sidebar-menu">
            <?php if(isset($permissions) && $permissions){  ?>
                <li class="active">
                    <?= $this->Html->link('<i class="fa fa-dashboard"></i> <span>' . __('home') . '</span>', 
                        array( 'plugin' => 'dashboard', 'controller' => 'dashboard', 'action' => 'index', 'admin' => true ),
                        array('escape' => false)); ?>
                </li>
                <!-- Start Plugin::Administration  -->
                <?php if(   (isset($permissions['Administrator']['view']) && ($permissions['Administrator']['view'] == true)) ||
                            (isset($permissions['Permission']['view']) && ($permissions['Permission']['view'] == true)) || 
                            (isset($permissions['Role']['view']) && ($permissions['Role']['view'] == true))) { ?>
                    <li class="treeview <?= ($this->params['plugin'] == 'administration'?' active':'');?>">
                        <a href="#">
                            <i class="fa fa-user"></i>
                            <span><?= __('administrator'); ?></span>
                            <i class="fa fa-angle-left pull-right"></i>
                        </a>
                        <ul class="treeview-menu">
                            <?php if( isset($permissions['Administrator']['view']) && ($permissions['Administrator']['view'] == true) ){ ?>
                                <li class="<?= ($this->params['controller'] == 'administrators'?' active':'');?>">
                                    <?php
                                        echo $this->Html->link(
                                            '<i class="fa fa-angle-double-right"></i>' . __('administrators'),
                                            array( 'plugin' => 'administration', 'controller' => 'administrators', 'action' => 'index', 'admin' => true ),
                                            array('escape' => false)
                                        );
                                    ?>
                                </li>
                            <?php } ?>
                            <?php if( isset($permissions['Permission']['view']) && ($permissions['Permission']['view'] == true) ){ ?>
                                <li class="<?= ($this->params['controller'] == 'permissions'?' active':'');?>">
                                    <?php
                                        echo $this->Html->link(
                                            '<i class="fa fa-angle-double-right"></i>' . __('permissions'),
                                            array( 'plugin' => 'administration', 'controller' => 'permissions', 'action' => 'index', 'admin' => true ),
                                            array('escape' => false)
                                        );
                                    ?>
                                </li>
                            <?php } ?>
                            <?php if( isset($permissions['Role']['view']) && ($permissions['Role']['view'] == true) ){ ?>
                                <li class="<?= ($this->params['controller'] == 'roles'?' active':'');?>">
                                    <?php
                                        echo $this->Html->link(
                                            '<i class="fa fa-angle-double-right"></i>' . __('roles'),
                                            array( 'plugin' => 'administration', 'controller' => 'roles', 'action' => 'index', 'admin' => true ),
                                            array('escape' => false)
                                        );
                                    ?>
                                </li>
                            <?php } ?>
                        </ul>
                    </li>
                <?php } ?>
                <!-- End Plugin::Administration  -->
                <!-- Start Plugin::Company  -->
                <?php if(   (isset($permissions['Shop']['view']) && ($permissions['Shop']['view'] == true))  ){ ?>
                    <li class="treeview<?= ($this->params['plugin'] == 'company'?' active':'');?>">
                        <a href="#">
                            <i class="fa fa-user"></i>
                            <span><?= __('shops'); ?></span>
                            <i class="fa fa-angle-left pull-right"></i>
                        </a>
                        <ul class="treeview-menu">
                            <?php if( isset($permissions['Mall']['view']) && ($permissions['Mall']['view'] == true) ){ ?>
                                <li class="<?= ($this->params['controller'] == 'malls'?' active':'');?>">
                                    <?php
                                        echo $this->Html->link(
                                            '<i class="fa fa-angle-double-right"></i>' . __('mall'),
                                            array( 'plugin' => 'company', 'controller' => 'malls', 'action' => 'index', 'admin' => true ),
                                            array('escape' => false)
                                        );
                                    ?>
                                </li>
                            <?php } ?>
                            <?php if( isset($permissions['Shop']['view']) && ($permissions['Shop']['view'] == true) ){ ?>
                                <li class="<?= ($this->params['controller'] == 'shops'?' active':'');?>">
                                    <?php
                                        echo $this->Html->link(
                                            '<i class="fa fa-angle-double-right"></i>' . __('shop'),
                                            array( 'plugin' => 'company', 'controller' => 'shops', 'action' => 'index', 'admin' => true ),
                                            array('escape' => false)
                                        );
                                    ?>
                                </li>
                            <?php } ?>
                        </ul>
                    </li>
                <?php } ?>
                <!-- End Plugin::Company  -->
                <!-- End Plugin::Member  -->
                <?php if( (isset($permissions['Member']['view']) && ($permissions['Member']['view'] == true)) || 
                            (isset($permissions['MGroup']['view']) && ($permissions['MGroup']['view'] == true) ) || 
                            (isset($permissions['MemberPromotion']['view']) && ($permissions['MemberPromotion']['view'] == true) ) || 
                            (isset($permissions['MemberVoucher']['view']) && ($permissions['MemberVoucher']['view'] == true) ) ){ ?>
                    <li class="treeview<?= ($this->params['plugin'] == 'member'?' active':'');?>">
                        <a href="#">
                            <i class="fa fa-users"></i>
                            <span><?= __('member'); ?></span>
                            <i class="fa fa-angle-left pull-right"></i>
                        </a>
                        <ul class="treeview-menu">
                            <?php if( isset($permissions['Member']['view']) && ($permissions['Member']['view'] == true) ){ ?>
                            <li class="<?= ($this->params['controller'] == 'members'?' active':'');?>">
                                <?php
                                echo $this->Html->link(
                                    '<i class="fa fa-angle-double-right"></i>' . __('members'),
                                    array( 'plugin' => 'member', 'controller' => 'members', 'action' => 'index', 'admin' => true ),
                                    array('escape' => false)
                                );
                            ?></li>
                            <?php } ?>
                            <?php if( isset($permissions['MGroup']['view']) && ($permissions['MGroup']['view'] == true) ){ ?>
                            <li class="<?= ($this->params['controller'] == 'm_groups'?' active':'');?>">
                                <?php
                                echo $this->Html->link(
                                    '<i class="fa fa-angle-double-right"></i>' . __d('member', 'member_group'),
                                    array( 'plugin' => 'member', 'controller' => 'm_groups', 'action' => 'index', 'admin' => true ),
                                    array('escape' => false)
                                );
                            ?></li>
                            <?php } ?>
                            <?php if( isset($permissions['MemberPromotion']['view']) && ($permissions['MemberPromotion']['view'] == true) ){ ?>
                            <li class="<?= ($this->params['controller'] == 'member_promotions'?' active':'');?>">
                                <?php
                                echo $this->Html->link(
                                    '<i class="fa fa-angle-double-right"></i>' . __d('member', 'member_promotion'),
                                    array( 'plugin' => 'member', 'controller' => 'member_promotions', 'action' => 'index', 'admin' => true ),
                                    array('escape' => false)
                                );
                            ?></li>
                            <?php } ?>
                            <?php if( isset($permissions['MemberVoucher']['view']) && ($permissions['MemberVoucher']['view'] == true) ){ ?>
                            <li class="<?= ($this->params['controller'] == 'member_vouchers'?' active':'');?>">
                                <?php
                                echo $this->Html->link(
                                    '<i class="fa fa-angle-double-right"></i>' . __d('member', 'member_voucher'),
                                    array( 'plugin' => 'member', 'controller' => 'member_vouchers', 'action' => 'index', 'admin' => true ),
                                    array('escape' => false)
                                );
                            ?></li>
                            <?php } ?>
                        </ul>
                    </li>
                <?php } ?>
                <!-- End Plugin::Member  -->
                <!-- Start Plugin::Coupon  -->
                <?php if( isset($permissions['Voucher']['view']) && ($permissions['Voucher']['view'] == true) ){ ?>
                    <li class="treeview<?= ($this->params['plugin'] == 'voucher'?' active':'');?>">
                        <a href="#">
                            <i class="fa fa-money"></i>
                            <span><?= __('voucher'); ?></span>
                            <i class="fa fa-angle-left pull-right"></i>
                        </a>
                        <ul class="treeview-menu">
                            <li class="<?= ($this->params['controller'] == 'vouchers'?' active':'');?>"><?php
                                echo $this->Html->link(
                                    '<i class="fa fa-angle-double-right"></i>' . __('voucher'),
                                    array( 'plugin' => 'voucher', 'controller' => 'vouchers', 'action' => 'index', 'admin' => true ),
                                    array('escape' => false)
                                );
                            ?></li>
                            <li class="<?= ($this->params['controller'] == 'voucher_distributions'?' active':'');?>"><?php
                                echo $this->Html->link(
                                    '<i class="fa fa-angle-double-right"></i>' . __d('voucher', 'distribution_voucher'),
                                    array( 'plugin' => 'voucher', 'controller' => 'voucher_distributions', 'action' => 'index', 'admin' => true ),
                                    array('escape' => false)
                                );
                            ?></li>
                        </ul>
                    </li>
                <?php } ?>
                <!-- End Plugin::Coupon  -->

                <!-- Start Plugin::Dictionary -->
                <?php if( isset($permissions['AgeGroup']['view']) && ($permissions['AgeGroup']['view'] == true) || 
                        isset($permissions['Country']['view']) && ($permissions['Country']['view'] == true) || 
                        isset($permissions['District']['view']) && ($permissions['District']['view'] == true) || 
                        // isset($permissions['Gender']['view']) && ($permissions['Gender']['view'] == true) ||
                        isset($permissions['PaymentMethod']['view']) && ($permissions['PaymentMethod']['view'] == true) ||
                        isset($permissions['Setting']['view']) && ($permissions['Setting']['view'] == true)){ ?>
                    <li class="treeview <?php echo ($this->params['plugin'] == 'dictionary'?' active':'');?>">
                        <a href="#">
                            <i class="fa fa-book"></i>
                            <span><?= __('dictionary'); ?></span>
                            <i class="fa fa-angle-left pull-right"></i>
                        </a>
                        <ul class="treeview-menu">
                            <?php if( isset($permissions['AgeGroup']['view']) && ($permissions['AgeGroup']['view'] == true)){ ?>
                                <li class="<?= ($this->params['controller'] == 'age_groups'?' active':'');?>"><?php
                                    echo $this->Html->link(
                                        '<i class="fa fa-angle-double-right"></i>' . __('age'),
                                        array( 'plugin' => 'dictionary', 'controller' => 'age_groups', 'action' => 'index', 'admin' => true ),
                                        array('escape' => false)
                                    );
                                ?></li>
                            <?php } ?>
                            <?php if( isset($permissions['District']['view']) && ($permissions['District']['view'] == true) ){ ?>
                                <li class="<?= ($this->params['controller'] == 'districts'?' active':'');?>"><?php
                                    echo $this->Html->link(
                                        '<i class="fa fa-angle-double-right"></i>' . __('district'),
                                        array( 'plugin' => 'dictionary', 'controller' => 'districts', 'action' => 'index', 'admin' => true ),
                                        array('escape' => false)
                                    );
                                ?></li>
                            <?php } ?>
                        </ul>
                    </li>
                <?php } ?>
                <!-- END Plugin::Dictionary -->

                <!-- Start Plugin::Dictionary -->
                <?php if( isset($permissions['Order']['view']) && ($permissions['Order']['view'] == true)){ ?>
                    <li class="treeview <?php echo ($this->params['plugin'] == 'order'?' active':'');?>">
                        <a href="#">
                            <i class="fa fa-book"></i>
                            <span><?= __d('order', 'order'); ?></span>
                            <i class="fa fa-angle-left pull-right"></i>
                        </a>
                        <ul class="treeview-menu">
                            <?php if( isset($permissions['Order']['view']) && ($permissions['Order']['view'] == true)){ ?>
                                <li class="<?= ($this->params['controller'] == 'orders'?' active':'');?>"><?php
                                    echo $this->Html->link(
                                        '<i class="fa fa-angle-double-right"></i>' . __d('order', 'order'),
                                        array( 'plugin' => 'order', 'controller' => 'orders', 'action' => 'index', 'admin' => true ),
                                        array('escape' => false)
                                    );
                                ?></li>
                            <?php } ?>
                        </ul>
                    </li>
                <?php } ?>
                <!-- END Plugin::Dictionary -->

                <!-- Start Plugin::Fact -->
                <?php if( (isset($permissions['Report']['gender']) && ($permissions['Report']['gender'] == true)) || 
                        (isset($permissions['Report']['birthday']) && ($permissions['Report']['birthday'] == true) ) || 
                        (isset($permissions['Report']['high_spending']) && ($permissions['Report']['high_spending'] == true) ) ||
                        (isset($permissions['Report']['high_visit']) && ($permissions['Report']['high_visit'] == true) ) ||
                        isset($permissions['ReportOrder']['report_by_mall']) && ($permissions['ReportOrder']['report_by_mall'] == true)||
                        isset($permissions['ReportOrder']['report_by_shop']) && ($permissions['ReportOrder']['report_by_shop'] == true)){ ?>
                    <li class="treeview <?= ($this->params['plugin'] == 'report'?' active':'');?>">
                        <a href="#">
                            <i class="fa fa-book"></i>
                            <span><?= __('report'); ?></span>
                            <i class="fa fa-angle-left pull-right"></i>
                        </a>
                        <ul class="treeview-menu">
                            <?php if( isset($permissions['Report']['gender']) && ($permissions['Report']['gender'] == true)){ ?>
                                <li class="<?= ($this->params['controller'] == 'customers' && $this->params['action'] == 'gender'?' active':'');?>"><?php
                                    echo $this->Html->link(
                                        '<i class="fa fa-angle-double-right"></i>' . __('gender'),
                                        array( 'plugin' => 'report', 'controller' => 'customers', 'action' => 'gender', 'admin' => true ),
                                        array('escape' => false)
                                    );
                                ?></li>
                            <?php } ?>
                            <?php if( isset($permissions['Report']['birthday']) && ($permissions['Report']['birthday'] == true)){ ?>
                                <li class="<?= ($this->params['controller'] == 'customers' && $this->params['action'] == 'birthday'?' active':'');?>"><?php
                                    echo $this->Html->link(
                                        '<i class="fa fa-angle-double-right"></i>' . __('birthday'),
                                        array( 'plugin' => 'report', 'controller' => 'customers', 'action' => 'birthday', 'admin' => true ),
                                        array('escape' => false)
                                    );
                                ?></li>
                            <?php } ?>
                            <?php if( isset($permissions['Report']['high_spending']) && ($permissions['Report']['high_spending'] == true)){ ?>
                                <li class="<?= ($this->params['controller'] == 'customers' && $this->params['action'] == 'high_spending'?' active':'');?>"><?php
                                    echo $this->Html->link(
                                        '<i class="fa fa-angle-double-right"></i>' . __('high_spending'),
                                        array( 'plugin' => 'report', 'controller' => 'customers', 'action' => 'high_spending', 'admin' => true ),
                                        array('escape' => false)
                                    );
                                ?></li>
                            <?php } ?>
                            <?php if( isset($permissions['Report']['high_visit']) && ($permissions['Report']['high_visit'] == true)){ ?>
                                <li class="<?= ($this->params['controller'] == 'customers' && $this->params['action'] == 'high_visit'?' active':'');?>"><?php
                                    echo $this->Html->link(
                                        '<i class="fa fa-angle-double-right"></i>' . __('high_visit'),
                                        array( 'plugin' => 'report', 'controller' => 'customers', 'action' => 'high_visit', 'admin' => true ),
                                        array('escape' => false)
                                    );
                                ?></li>
                            <?php } ?>
                            <?php if( isset($permissions['ReportOrder']['report_by_mall']) && ($permissions['ReportOrder']['report_by_mall'] == true)){ ?>
                                <li class="<?= ($this->params['controller'] == 'transactions' && $this->params['action'] == 'report_by_mall'?' active':'');?>"><?php
                                    echo $this->Html->link(
                                        '<i class="fa fa-angle-double-right"></i>' . __('spending_by_mall'),
                                        array( 'plugin' => 'report', 'controller' => 'transactions', 'action' => 'report_by_mall', 'admin' => true ),
                                        array('escape' => false)
                                    );
                                ?></li>
                            <?php } ?>
                            <?php if( isset($permissions['ReportOrder']['report_by_shop']) && ($permissions['ReportOrder']['report_by_shop'] == true)){ ?>
                                <li class="<?= ($this->params['controller'] == 'transactions' && $this->params['action'] == 'report_by_shop'?' active':'');?>"><?php
                                    echo $this->Html->link(
                                        '<i class="fa fa-angle-double-right"></i>' . __('spending_by_shop'),
                                        array( 'plugin' => 'report', 'controller' => 'transactions', 'action' => 'report_by_shop', 'admin' => true ),
                                        array('escape' => false)
                                    );
                                ?></li>
                            <?php } ?>
                        </ul>
                    </li>
                <?php } ?>
                <!-- END Plugin::Fact -->

                <!-- Start Plugin::Fact -->
                <?php if( isset($permissions['Fact']['view']) && ($permissions['Fact']['view'] == true) ){ ?>
                    <li class="treeview <?= ($this->params['plugin'] == 'fact'?' active':'');?>">
                        <a href="#">
                            <i class="fa fa-book"></i>
                            <span><?= __('fact'); ?></span>
                            <i class="fa fa-angle-left pull-right"></i>
                        </a>
                        <ul class="treeview-menu">
                            <?php if( isset($permissions['Fact']['view']) && ($permissions['Fact']['view'] == true)){ ?>
                                <li class="<?= ($this->params['controller'] == 'facts'?' active':'');?>"><?php
                                    echo $this->Html->link(
                                        '<i class="fa fa-angle-double-right"></i>' . __('fact'),
                                        array( 'plugin' => 'fact', 'controller' => 'facts', 'action' => 'index', 'admin' => true ),
                                        array('escape' => false)
                                    );
                                ?></li>
                            <?php } ?>
                        </ul>
                    </li>
                <?php } ?>
                <!-- END Plugin::Fact -->

            <?php }else{ ?>
				<li class="text-center active">
					<?php 
						echo $this->Html->link('Sign In First', array(
							'plugin' => 'administration',
							'controller' => 'administrators',
							'action' => 'login',
							'admin' => true
						)); 
					?>
				</li>
            <?php } ?>
        </ul>
	</section>
</aside>
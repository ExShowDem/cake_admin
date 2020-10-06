<?php echo $this->Html->css('datepicker/datepicker3'); ?>
<?php echo $this->Html->css('chartjs/Chart.min'); ?>
<?php echo $this->Html->css('dashboard'); ?>

<?php
	echo $this->Html->script('plugins/datepicker/bootstrap-datepicker', array('inline' => false));
	echo $this->Html->script('plugins/chartjs/Chart.min', array('inline' => false));
	echo $this->Html->script('CakeAdminLTE/pages/admin_dashboard', array('inline' => false));
?>
<script type="text/javascript">
	$(document).ready(function(){
		// ADMIN_DASHBOARD.url_gender_member = '<?= Router::url(array('plugin' => 'dashboard', 'controller' => 'dashboard', 'action' => 'report_gender_member')); ?>';
		// ADMIN_DASHBOARD.url_birthday_member = '<?= Router::url(array('plugin' => 'dashboard', 'controller' => 'dashboard', 'action' => 'report_birthday_member')); ?>';
		// ADMIN_DASHBOARD.url_report_high_spending = '<?= Router::url(array('plugin' => 'dashboard', 'controller' => 'dashboard', 'action' => 'report_high_spending')); ?>';
		// ADMIN_DASHBOARD.url_report_visit = '<?= Router::url(array('plugin' => 'dashboard', 'controller' => 'dashboard', 'action' => 'report_visit')); ?>';
       
		// ADMIN_DASHBOARD.init_page();
	});
</script>
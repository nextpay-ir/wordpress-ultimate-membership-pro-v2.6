<?php 

$url = get_admin_url() . 'admin.php?page=ihc_manage';


$tab = 'dashboard';
if(isset($_REQUEST['tab'])) $tab = $_REQUEST['tab'];

$tabs_arr = array(
					'users' => __('Users', 'ihc'),
					'levels' => __('Levels', 'ihc'),
					'payment_settings' => __('Payment Services', 'ihc'),
					'locker' => __('Inside Lockers', 'ihc'),
					'showcases' => __('Showcases', 'ihc'),
					'social_login' => __("Social Login", 'ihc'),
					'coupons' => __("Coupons", "ihc"),
					'block_url' => __('URL Blocks', 'ihc'),
					'payments' => __('Transactions', 'ihc'),
					'notifications' => __('Notifications', 'ihc'),
					'opt_in' => __('Opt-in Settings', 'ihc'),						
					'general' => __('General Options', 'ihc'),				
					'help' => __('Help', 'ihc'),
				  );
?>
<div class="ihc-dashboard-wrap">
	<div class="ihc-admin-header">
		<div class="ihc-top-menu-section">
			<div class="ihc-dashboard-logo">
			<a href="<?php echo $url.'&tab=dashboard';?>">
				<img src="<?php echo IHC_URL;?>admin/assets/images/dashboard-logo.jpg"/>
			</a>
			</div>
			<div class="ihc-dashboard-menu">
				<ul>
				<?php 
					foreach($tabs_arr as $k=>$v){
						$selected = '';
						$menu_tab = $tab;
						switch($tab){
							case 'register':	
											$menu_tab='showcases';
											break;
							case 'login':	
											$menu_tab='showcases';
											break;	
							case 'subscription_plan':	
											$menu_tab='showcases';
											break;
							case 'account_page':	
											$menu_tab='showcases';
											break;													
						}
						
						
						if($menu_tab==$k) $selected = 'selected';						
						?>
							<li class="<?php echo $selected;?>">
								<a href="<?php echo $url.'&tab='.$k;?>" title="<?php echo $v;?>">
									<div class="ihc-page-title">
										<i class="fa-ihc fa-ihc-menu fa-<?php echo $k;?>-ihc"></i>
										<div><?php echo $v;?></div>								
									</div>						
								</a>
							</li>	
						<?php 	
					}
				?>
		
				</ul>
			</div>
		</div>
	</div>
	<?php 
		//tabs
		switch($tab){
			case 'dashboard':
				include_once IHC_PATH . 'admin/includes/tabs/dashboard.php';
			break;
			case 'users':
				include_once IHC_PATH . 'admin/includes/tabs/users.php';
			break;
			case 'levels':
				include_once IHC_PATH . 'admin/includes/tabs/levels.php';
			break;
			case 'locker':
				include_once IHC_PATH . 'admin/includes/tabs/locker.php';
			break;
			case 'register':
				include_once IHC_PATH . 'admin/includes/tabs/register.php';
			break;
			case 'login':
				include_once IHC_PATH . 'admin/includes/tabs/login.php';
			break;
			case 'payments':
				include_once IHC_PATH . 'admin/includes/tabs/list_payments.php';
			break;
			case 'general':
				include_once IHC_PATH . 'admin/includes/tabs/general.php';
			break;		
			case 'block_url':
				include_once IHC_PATH . 'admin/includes/tabs/block_url.php';
			break;
			case 'opt_in':
				include_once IHC_PATH . 'admin/includes/tabs/opt_in.php';
			break;
			case 'payment_settings':
				include_once IHC_PATH . 'admin/includes/tabs/payment_settings.php';
			break;
			case 'help':
				include_once IHC_PATH . 'admin/includes/tabs/help.php';	
			break;
			case 'notifications':
				include_once IHC_PATH . 'admin/includes/tabs/notifications.php';
			break;
			case 'showcases':
				include_once IHC_PATH . 'admin/includes/tabs/showcases.php';	
			break;
			case 'subscription_plan':
				include_once IHC_PATH . 'admin/includes/tabs/subscription_plan.php';	
			break;
			case 'social_login':
				include_once IHC_PATH . 'admin/includes/tabs/social_login.php';
			break;
			case 'account_page':
				include_once IHC_PATH . 'admin/includes/tabs/account_page.php';
			break;
			case 'coupons':
				include_once IHC_PATH . 'admin/includes/tabs/coupons.php';
			break;
		}
	
	?>
		
</div>

<?php wp_enqueue_script('ihc-back_end');?>

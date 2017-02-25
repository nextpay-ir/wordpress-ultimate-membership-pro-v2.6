<div class="ihc-subtab-menu">
	<a class="ihc-subtab-menu-item" href="<?php echo $url.'&tab='.$tab.'&subtab=defaults';?>"><?php _e('Defaults Settings', 'ihc');?></a>
	<a class="ihc-subtab-menu-item" href="<?php echo $url.'&tab='.$tab.'&subtab=captcha';?>"><?php _e('Captcha', 'ihc');?></a>
	<a class="ihc-subtab-menu-item" href="<?php echo $url.'&tab='.$tab.'&subtab=msg';?>"><?php _e('Messages', 'ihc');?></a>
	<a class="ihc-subtab-menu-item" href="<?php echo $url.'&tab='.$tab.'&subtab=menus';?>"><?php _e('Menus', 'ihc');?></a>
	<a class="ihc-subtab-menu-item" href="<?php echo $url.'&tab='.$tab.'&subtab=pay_settings';?>"><?php _e('Payments', 'ihc');?></a>
	<a class="ihc-subtab-menu-item" href="<?php echo $url.'&tab='.$tab.'&subtab=custom_currency';?>"><?php _e('Custom Currencies', 'ihc');?></a>
	<a class="ihc-subtab-menu-item" href="<?php echo $url.'&tab='.$tab.'&subtab=notifications';?>"><?php _e('Notifications', 'ihc');?></a>
	<a class="ihc-subtab-menu-item" href="<?php echo $url.'&tab='.$tab.'&subtab=redirect_links';?>"><?php _e('Redirect Links', 'ihc');?></a>	
	<a class="ihc-subtab-menu-item" href="<?php echo $url.'&tab='.$tab.'&subtab=double_email_verification';?>"><?php _e('Double E-mail Verification', 'ihc');?></a>		
	<a class="ihc-subtab-menu-item" href="<?php echo $url.'&tab='.$tab.'&subtab=extra_settings';?>"><?php _e('Extra Settings', 'ihc');?></a>		
</div>
<?php 
$pages = ihc_get_all_pages();//getting pages

$subtab = 'defaults';
if (isset($_REQUEST['subtab'])) $subtab = $_REQUEST['subtab'];

switch ($subtab){
	case 'defaults':
		ihc_save_update_metas('general-defaults');//save update metas
		$meta_arr = ihc_return_meta_arr('general-defaults');//getting metas		
		echo ihc_check_default_pages_set();//set default pages message
		echo ihc_check_payment_gateways();
		?>
			<form action="" method="post">
				<div class="ihc-stuffbox">
					<h3><?php _e('Default Pages:', 'ihc');?></h3>
					<div class="inside">	
					
						<div class="iump-form-line">
							<span class="iump-labels-special"><?php _e('Register:', 'ihc');?></span>
							<select name="ihc_general_register_default_page">
								<option value="-1" <?php if($meta_arr['ihc_general_register_default_page']==-1)echo 'selected';?> >...</option>
								<?php 
									if ($pages){
										foreach ($pages as $k=>$v){
											?>
												<option value="<?php echo $k;?>" <?php if($meta_arr['ihc_general_register_default_page']==$k)echo 'selected';?> ><?php echo $v;?></option>
											<?php 
										}						
									}
								?>
							</select>
							<?php echo ihc_general_options_print_page_links($meta_arr['ihc_general_register_default_page']);?>
						</div>	
						
						<div class="iump-form-line">
							<span class="iump-labels-special"><?php _e('Subscription Plan:', 'ihc');?></span>
							<select name="ihc_subscription_plan_page">
								<option value="-1" <?php if($meta_arr['ihc_subscription_plan_page']==-1)echo 'selected';?> >...</option>
								<?php 
									if ($pages){
										foreach ($pages as $k=>$v){
											?>
												<option value="<?php echo $k;?>" <?php if($meta_arr['ihc_subscription_plan_page']==$k)echo 'selected';?> ><?php echo $v;?></option>
											<?php 
										}						
									}
								?>
							</select>
							<?php echo ihc_general_options_print_page_links($meta_arr['ihc_subscription_plan_page']);?>
						</div>				
						
						<div class="iump-form-line">
							<span class="iump-labels-special"><?php _e('Login Page:', 'ihc');?></span>
							<select name="ihc_general_login_default_page">
								<option value="-1" <?php if($meta_arr['ihc_general_login_default_page']==-1)echo 'selected';?> >...</option>
								<?php 
									if ($pages){
										foreach ($pages as $k=>$v){
											?>
												<option value="<?php echo $k;?>" <?php if($meta_arr['ihc_general_login_default_page']==$k)echo 'selected';?> ><?php echo $v;?></option>
											<?php 
										}						
									}
								?>
							</select>
							<?php echo ihc_general_options_print_page_links($meta_arr['ihc_general_login_default_page']);?>
						</div>
						
						<div class="iump-form-line">
							<span class="iump-labels-special"><?php _e('Logout Page:', 'ihc');?></span>
							<select name="ihc_general_logout_page">
								<option value="-1" <?php if($meta_arr['ihc_general_logout_page']==-1)echo 'selected';?> >...</option>
								<?php 
									if ($pages){
										foreach ($pages as $k=>$v){
											?>
												<option value="<?php echo $k;?>" <?php if($meta_arr['ihc_general_logout_page']==$k)echo 'selected';?> ><?php echo $v;?></option>
											<?php 
										}						
									}
								?>
							</select>	
							<?php echo ihc_general_options_print_page_links($meta_arr['ihc_general_logout_page']);?>		
						</div>	
		
						<div class="iump-form-line">
							<span class="iump-labels-special"><?php _e('User Account Page:', 'ihc');?></span>
							<select name="ihc_general_user_page">
								<option value="-1" <?php if($meta_arr['ihc_general_user_page']==-1)echo 'selected';?> >...</option>
								<?php 
									if ($pages){
										foreach ($pages as $k=>$v){
											?>
												<option value="<?php echo $k;?>" <?php if($meta_arr['ihc_general_user_page']==$k)echo 'selected';?> ><?php echo $v;?></option>
											<?php 
										}						
									}
								?>
							</select>	
							<?php echo ihc_general_options_print_page_links($meta_arr['ihc_general_user_page']);?>		
						</div>	
						
						<div class="iump-form-line">
							<span class="iump-labels-special"><?php _e('TOS Page:', 'ihc');?></span>
							<select name="ihc_general_tos_page">
								<option value="-1" <?php if($meta_arr['ihc_general_tos_page']==-1)echo 'selected';?> >...</option>
								<?php 
									if ($pages){
										foreach ($pages as $k=>$v){
											?>
												<option value="<?php echo $k;?>" <?php if($meta_arr['ihc_general_tos_page']==$k)echo 'selected';?> ><?php echo $v;?></option>
											<?php 
										}						
									}
								?>
							</select>	
							<?php echo ihc_general_options_print_page_links($meta_arr['ihc_general_tos_page']);?>		
						</div>	
									
						<div class="iump-form-line">
							<span class="iump-labels-special"><?php _e('Lost Password:', 'ihc');?></span>
							<select name="ihc_general_lost_pass_page">
								<option value="-1" <?php if($meta_arr['ihc_general_lost_pass_page']==-1)echo 'selected';?> >...</option>
								<?php 
									if ($pages){
										foreach ($pages as $k=>$v){
											?>
												<option value="<?php echo $k;?>" <?php if($meta_arr['ihc_general_lost_pass_page']==$k)echo 'selected';?> ><?php echo $v;?></option>
											<?php 
										}						
									}
								?>
							</select>	
							<?php echo ihc_general_options_print_page_links($meta_arr['ihc_general_lost_pass_page']);?>		
						</div>				
						
						<div class="ihc-wrapp-submit-bttn">
							<input type="submit" value="<?php _e('Save', 'ihc');?>" name="ihc_save" class="button button-primary button-large" />
						</div>							
					</div>
				</div>			
			
				<div class="ihc-stuffbox">
					<h3><?php _e('Default Redirects', 'ihc');?></h3>
					<div class="inside">	
						<div class="iump-form-line">
							<span class="iump-labels-special"><?php _e('Default Redirect Page:', 'ihc');?></span>
							<select name="ihc_general_redirect_default_page">
								<option value="-1" <?php if ($meta_arr['ihc_general_redirect_default_page']==-1)echo 'selected';?> >...</option>
								<?php 
									$pages_arr = $pages + ihc_get_redirect_links_as_arr_for_select();
									if ($pages_arr){
										foreach ($pages_arr as $k=>$v){
											?>
												<option value="<?php echo $k;?>" <?php if($meta_arr['ihc_general_redirect_default_page']==$k)echo 'selected';?> ><?php echo $v;?></option>
											<?php 
										}						
									}
								?>
							</select>	
							<?php echo ihc_general_options_print_page_links($meta_arr['ihc_general_redirect_default_page']);?>				
						</div>		
						
						<div class="iump-form-line">
							<span class="iump-labels-special"><?php _e('After LogOut:', 'ihc');?></span>
							<select name="ihc_general_logout_redirect">
								<option value="-1" <?php if($meta_arr['ihc_general_logout_redirect']==-1)echo 'selected';?> ><?php _e('Do Not Redirect', 'ihc');?></option>
								<?php 
									$pages_arr = $pages + ihc_get_redirect_links_as_arr_for_select();
									if ($pages_arr){
										foreach ($pages_arr as $k=>$v){
											?>
												<option value="<?php echo $k;?>" <?php if($meta_arr['ihc_general_logout_redirect']==$k)echo 'selected';?> ><?php echo $v;?></option>
											<?php 
										}						
									}
								?>
							</select>	
							<?php echo ihc_general_options_print_page_links($meta_arr['ihc_general_logout_redirect']);?>						
						</div>	
						
						<div class="iump-form-line">
							<span class="iump-labels-special"><?php _e('After Registration:', 'ihc');?></span>
							<select name="ihc_general_register_redirect">
								<option value="-1" <?php if($meta_arr['ihc_general_register_redirect']==-1)echo 'selected';?> ><?php _e('Do Not Redirect', 'ihc');?></option>
								<?php 
									$pages_arr = $pages + ihc_get_redirect_links_as_arr_for_select();
									if ($pages_arr){
										foreach ($pages_arr as $k=>$v){
											?>
												<option value="<?php echo $k;?>" <?php if($meta_arr['ihc_general_register_redirect']==$k)echo 'selected';?> ><?php echo $v;?></option>
											<?php 
										}						
									}
								?>
							</select>		
							<?php echo ihc_general_options_print_page_links($meta_arr['ihc_general_register_redirect']);?>							
						</div>		
						
						<div class="iump-form-line">
							<span class="iump-labels-special"><?php _e('After Login:', 'ihc');?></span>
							<select name="ihc_general_login_redirect">
								<option value="-1" <?php if($meta_arr['ihc_general_login_redirect']==-1)echo 'selected';?> ><?php _e('Do Not Redirect', 'ihc');?></option>
								<?php 
									$pages_arr = $pages + ihc_get_redirect_links_as_arr_for_select();
									if ($pages_arr){
										foreach ($pages_arr as $k=>$v){
											?>
												<option value="<?php echo $k;?>" <?php if($meta_arr['ihc_general_login_redirect']==$k)echo 'selected';?> ><?php echo $v;?></option>
											<?php 
										}						
									}
								?>
							</select>	
							<?php echo ihc_general_options_print_page_links($meta_arr['ihc_general_login_redirect']);?>									
						</div>										
		
						<div style="margin-top: 15px;">
							<input type="submit" value="<?php _e('Save', 'ihc');?>" name="ihc_save" class="button button-primary button-large" />
						</div>									
					</div>
				</div>			
			</form>
		<?php 
	break;
	case 'captcha':
		ihc_save_update_metas('general-captcha');//save update metas
		$meta_arr = ihc_return_meta_arr('general-captcha');//getting metas
		echo ihc_check_default_pages_set();//set default pages message
		echo ihc_check_payment_gateways();
		?>
					<form action="" method="post">
						<div class="ihc-stuffbox">
							<h3>ReCaptcha</h3>
							<div class="inside">
								<div>
									<?php _e('Public Key:', 'ihc');?> <input type="text" name="ihc_recaptcha_public" value="<?php echo $meta_arr['ihc_recaptcha_public'];?>" class="ihc-deashboard-middle-text-input"/>
								</div>
								<div>
									<?php _e('Private Key:', 'ihc');?> <input type="text" name="ihc_recaptcha_private" value="<?php echo $meta_arr['ihc_recaptcha_private'];?>" class="ihc-deashboard-middle-text-input" />
								</div>		
								<div class=""><?php _e('Get Public and Private Keys from', 'ihc');?> <a href="https://www.google.com/recaptcha/admin#list" target="_blank"><?php _e('here', 'ihc');?></a>.</div>		
								<div style="margin-top: 15px;">
									<input type="submit" value="<?php _e('Save', 'ihc');?>" name="ihc_save" onClick="" class="button button-primary button-large" />
								</div>					
							</div>
						</div>						
					</form>
				<?php 		
	break;
	case 'msg':
		ihc_save_update_metas('general-msg');//save update metas
		$meta_arr = ihc_return_meta_arr('general-msg');//getting metas
		echo ihc_check_default_pages_set();//set default pages message
		echo ihc_check_payment_gateways();
		?>
					<form action="" method="post">
						<div class="ihc-stuffbox">
							<h3><?php _e('Custom Messages', 'ihc');?></h3>
							<div class="inside">		
								<div>
									<div class="iump-labels-special"><?php _e('Update Successfully Message:', 'ihc');?></div>
									<textarea name="ihc_general_update_msg" class="ihc-dashboard-textarea"><?php echo ihc_correct_text($meta_arr['ihc_general_update_msg']);?></textarea>
								</div>				
								
								<div style="margin-top: 15px;">
									<input type="submit" value="<?php _e('Save', 'ihc');?>" name="ihc_save" class="button button-primary button-large" />
								</div>	
							</div>
						</div>					
					</form>
				<?php 		
	break;
	case 'menus':
		$nav_menus = wp_get_nav_menus();
		?>
		<form action="" method="post">
			<div class="ihc-stuffbox">
				<h3><?php _e('Customize Your Menu', 'ihc');?></h3>
				<div class="inside">
					<select name="menu_id" onChange="window.location = '<?php echo $url.'&tab='.$tab.'&subtab=menus&menu_id=';?>'+this.value;" style="min-width: 400px;margin-bottom: 20px;">
						<option value="0"><?php _e('Select a Menu', 'ihc');?></option>
						<?php foreach ( $nav_menus as $menu ){ ?>
							<?php $selected = (!empty($_GET['menu_id']) && $_GET['menu_id']==$menu->term_id) ? 'selected' : ''; ?>
							<option <?php echo $selected; ?> value="<?php echo $menu->term_id; ?>">
								<?php echo wp_html_excerpt( $menu->name, 40, '&hellip;' ); ?>
							</option>
						<?php } ?>
					</select>
					<div>
						<?php 
							if (!empty($_GET['menu_id'])){
								///update 
								if (!empty($_REQUEST['ihc_save'])){
									foreach ($_REQUEST['db_menu_id'] as $v){
										if (isset($_REQUEST['ihc_mb_who_menu_type-'.$v]) && isset($_REQUEST['ihc_menu_mb_type-'.$v])){
											update_post_meta( $v, 'ihc_mb_who_menu_type', $_REQUEST['ihc_mb_who_menu_type-'.$v]);
											update_post_meta( $v, 'ihc_menu_mb_type', $_REQUEST['ihc_menu_mb_type-'.$v]);											
										}
									}
								}
								
								///list
								$menu_items = wp_get_nav_menu_items( $_GET['menu_id'], array( 'post_status' => 'any' ) );
								foreach ($menu_items as $obj){
									?>
									<div style="padding: 5px 0px; margin: 5px 0px; border-top: 1px solid #c3c3c3;">
										<div class="ihc-menu-page">
											"<?php echo $obj->title;?>" <span><?php _e('link', 'ihc');?></span>
										</div>		
										<input type="hidden" name="db_menu_id[]" value="<?php echo $obj->ID;?>" />
										<div class="ihc-class ihc-padding">
											<select class="ihc-select" name="ihc_menu_mb_type-<?php echo $obj->ID; ?>" style="width: 300px;">
												<option value="block" <?php if ($obj->ihc_menu_mb_type=='block')echo 'selected';?> ><?php _e('Block Menu Item Only', 'ihc');?></option>
												<option value="show" <?php if ($obj->ihc_menu_mb_type=='show')echo 'selected';?> ><?php _e('Show Menu Item Only', 'ihc');?></option>									
											</select>
										</div>	
										<div  class="ihc-padding"  style="margin-bottom:10px;">
											<label class="ihc-bold" style="display:block;"><?php _e('for:', 'ihc');?></label>
											<?php
												$posible_values = array('all'=>__('All', 'ihc'), 'reg'=>__('Registered Users', 'ihc'), 'unreg'=>__('Unregistered Users', 'ihc') );
												$levels = get_option('ihc_levels');
												if ($levels){
													foreach ($levels as $id => $level){
														$posible_values[$id] = $level['name'];
													}
												}
												?>
												<select id="" onChange="ihc_writeTagValue(this, '#ihc_mb_who_hidden-<?php echo $obj->ID;?>', '#ihc_tags_field-<?php echo $obj->ID;?>', '<?php echo $obj->ID;?>_ihc_select_tag_' );" style="width: 300px;">
													<option value="-1" selected>...</option>
													<?php 
														foreach($posible_values as $k=>$v){
															?>
															<option value="<?php echo $k;?>"><?php echo $v;?></option>	
															<?php 
														}
													?>
												</select>
										</div>
										<div id="ihc_tags_field-<?php echo $obj->ID;?>">
							            	<?php
							            		
							                    if ($obj->ihc_mb_who_menu_type){
							                    	if (!empty($values)) unset($values);
							                    	if (strpos($obj->ihc_mb_who_menu_type, ',')!==FALSE){
							                    		$values = explode(',', $obj->ihc_mb_who_menu_type);
							                    	} else {
							                        	$values[] = $obj->ihc_mb_who_menu_type;			
							                        }
							                        foreach ($values as $value) { ?>
							                        	<div id="<?php echo $obj->ID;?>_ihc_select_tag_<?php echo $value;?>" class="ihc-tag-item">
							                        		<?php echo $posible_values[$value];?>
							                        		<div class="ihc-remove-tag" onclick="ihcremoveTag('<?php echo $value;?>', '#<?php echo $obj->ID;?>_ihc_select_tag_', '#ihc_mb_who_hidden-<?php echo $obj->ID;?>');" title="<?php _e('Removing tag', 'ihc');?>">x</div>
							                        	</div>
							                            <?php
							                        }//end of foreach ?>
							                    <div class="ihc-clear"></div>
							                    <?php }//end of if ?>
															
										</div>
										<div class="ihc-clear"></div>
										<input type="hidden" id="ihc_mb_who_hidden-<?php echo $obj->ID;?>" name="ihc_mb_who_menu_type-<?php echo $obj->ID; ?>" value="<?php echo $obj->ihc_mb_who_menu_type;?>" />
										<div class="clear"></div>																		
									</div>
									
									<?php 	
								}								
							}
						?>
					</div>
					
					<div style="margin-top: 15px;">
						<input type="submit" value="<?php _e('Save', 'ihc');?>" name="ihc_save" class="button button-primary button-large" />
					</div>			
				</div>
			</div>
		</form>
		<?php 
	break;
	
	case 'extra_settings':
		if (isset($_POST['ihc_save'])){
			ihc_save_update_metas('extra_settings');//save update metas
		}		
		$meta_arr = ihc_return_meta_arr('extra_settings');//getting metas
		echo ihc_check_default_pages_set();//set default pages message
		echo ihc_check_payment_gateways();
		?>
			<form action="" method="post">
			
				<div class="ihc-stuffbox">
					<h3> <?php _e("Grace Period:", 'ihc');?></h3>
					<div class="inside">
						<select name="ihc_grace_period"><?php 
							for ($i=0;$i<32;$i++){
								$selected = ($meta_arr['ihc_grace_period']==$i) ? 'selected' : '';
								?>
									<option value="<?php echo $i;?>" <?php echo $selected;?>><?php echo $i . ' ' . __('Days', 'ihc');?></option>
								<?php 	
							}
						?></select>			
						<div style="margin-top: 15px;">
							<input type="submit" value="<?php _e('Save', 'ihc');?>" name="ihc_save" class="button button-primary button-large" />
						</div>		
					</div>
				</div>
				
				<div class="ihc-stuffbox">
					<h3> <?php _e("Debugging Payment Data:", 'ihc');?></h3>
					<div class="inside">
						
						<input type="checkbox" onClick="check_and_h(this, '#ihc_debug_payments_db');" <?php if ($meta_arr['ihc_debug_payments_db']) echo 'checked';?> />
						<input type="hidden" value="<?php echo $meta_arr['ihc_debug_payments_db'];?>" name="ihc_debug_payments_db" id="ihc_debug_payments_db" />
								
						<div style="margin-top: 15px;">
							<input type="submit" value="<?php _e('Save', 'ihc');?>" name="ihc_save" class="button button-primary button-large" />
						</div>		
					</div>
				</div>

				<div class="ihc-stuffbox">
					<h3> <?php _e("Upload File Accepted Extensions:", 'ihc');?></h3>
					<div class="inside">
						<textarea name="ihc_upload_extensions" style="width: 300px;"><?php echo $meta_arr['ihc_upload_extensions'];?></textarea>
						<div><?php  _e("Write the extensions with comma between values! ex: pdf,jpg,mp3");?></div>							
						<div style="margin-top: 15px;">
							<input type="submit" value="<?php _e('Save', 'ihc');?>" name="ihc_save" class="button button-primary button-large" />
						</div>		
					</div>
				</div>

				<div class="ihc-stuffbox">
					<h3> <?php _e("Upload File Maximum File Size:", 'ihc');?></h3>
					<div class="inside">
						<input type="number" value="<?php echo $meta_arr['ihc_upload_max_size'];?>" name="ihc_upload_max_size" min="0.1" step="0.1" /> MB					
						<div style="margin-top: 15px;">
							<input type="submit" value="<?php _e('Save', 'ihc');?>" name="ihc_save" class="button button-primary button-large" />
						</div>		
					</div>
				</div>				

				<div class="ihc-stuffbox">
					<h3> <?php _e("Avatar Maximum File Size:", 'ihc');?></h3>
					<div class="inside">
						<input type="number" value="<?php echo $meta_arr['ihc_avatar_max_size'];?>" name="ihc_avatar_max_size" min="0.1" step="0.1" /> MB					
						<div style="margin-top: 15px;">
							<input type="submit" value="<?php _e('Save', 'ihc');?>" name="ihc_save" class="button button-primary button-large" />
						</div>		
					</div>
				</div>						
				
			</form>		
		<?php 
	break;
	case 'notifications':
		ihc_save_update_metas('notifications');//save update metas
		$meta_arr = ihc_return_meta_arr('notifications');//getting metas
		echo ihc_check_default_pages_set();//set default pages message
		echo ihc_check_payment_gateways();
		?>
		<form action="" method="post">
			<div class="ihc-stuffbox">
				<h3><?php _e('Notifications Settings', 'ihc');?></h3>
				<div class="inside">		
					<div>
						<div class="iump-labels-special"><?php _e("'From' E-mail Address:", 'ihc');?></div>
						<input type="text" name="ihc_notification_email_from" value="<?php echo $meta_arr['ihc_notification_email_from'];?>" style="width: 300px;" />
					</div>	
					<div>
						<div class="iump-labels-special"><?php _e("'From' Name:", 'ihc');?></div>
						<input type="text" name="ihc_notification_name" value="<?php echo $meta_arr['ihc_notification_name'];?>" style="width: 300px;" />
					</div>						
					<div style="margin-top: 15px;">
						<div class="iump-labels-special"><?php _e("'Before Expire' Time Interval:", 'ihc');?></div>
						<input type="number" min="1" name="ihc_notification_before_time" value="<?php echo $meta_arr['ihc_notification_before_time'];?>" style="width: 300px;" /> <?php _e("Days", 'ihc');?>
					</div>										
					<div style="margin-top: 15px;">
						<input type="submit" value="<?php _e('Save', 'ihc');?>" name="ihc_save" class="button button-primary button-large" />
					</div>	
				</div>
			</div>					
		</form>
		<?php 
	break;
	case 'pay_settings':
		ihc_save_update_metas('payment');//save update metas
		$meta_arr = ihc_return_meta_arr('payment');//getting metas
		echo ihc_check_default_pages_set();//set default pages message
		echo ihc_check_payment_gateways();
		?>
		<form action="" method="post">
			<div class="ihc-stuffbox">
				<h3><?php _e('Currency Settings:', 'ihc');?></h3>
				<div class="inside">		
					<div class="iump-form-line">
						<select name="ihc_currency">
							<?php 
								$currency_arr = ihc_get_currencies_list('all');
								$custom_currencies = ihc_get_currencies_list('custom');
								foreach ($currency_arr as $k=>$v){
									?>
									<option value="<?php echo $k?>" <?php if ($k==$meta_arr['ihc_currency']) echo 'selected';?> >
										<?php echo $v;?>
										<?php if (is_array($custom_currencies) && in_array($v, $custom_currencies))  _e(" (Custom Currency)");?>
									</option>
									<?php 
								}
							?>
						</select>
						<p style="font-weight:bold;"><?php _e('Check which Payment Service supports the next currency and deactivate the other Payment Services.', 'ihc');?></p>
						<p>You can add new Currencies from <a href="<?php echo $url.'&tab=general&subtab=custom_currency';?>">here</a></p>				
					</div>
					<div class="ihc-wrapp-submit-bttn iump-submit-form">
						<input type="submit" value="<?php _e('Save', 'ihc');?>" name="ihc_save" class="button button-primary button-large" />
					</div>			
				</div>
			</div>
			<div class="ihc-stuffbox">
				<h3><?php _e('Default Payment Gateway:', 'ihc');?></h3>
				<div class="inside">		
					<div class="iump-form-line">
						<select name="ihc_payment_selected">
							<?php 
								$payment_arr = array(
														'nextpay' => 'Nextpay',
														'bank_transfer' => 'Bank Transfer',
														);
								foreach($payment_arr as $k=>$v){
									$active = (ihc_check_payment_available($k)) ? __('Active', 'ihc') : __('Inactive', 'ihc');  
									?>
									<option value="<?php echo $k?>" <?php if ($k==$meta_arr['ihc_payment_selected']) echo 'selected';?> >
										<?php echo $v . ' - ' . $active;?>
									</option>
									<?php 
								}
							?>
						</select>
						<div class="ihc-dashboard-inform-message"><?php _e('When no multi-payment activated or no payment selected or required.');?></div>
						<div class="ihc-dangerbox"><?php _e("Be sure that your selected Payment Gateway it's activated and properly set!");?></div>				
					</div>
					<div class="ihc-wrapp-submit-bttn iump-submit-form">
						<input type="submit" value="<?php _e('Save', 'ihc');?>" name="ihc_save" class="button button-primary button-large" />
					</div>			
				</div>
			</div>					
		</form>
		<?php 
	break;
	
	case 'redirect_links':
		if (!empty($_POST['url'])){
			ihc_add_new_redirect_link($_POST);
		} else if (isset($_POST['delete_redirect_link'])){
			ihc_delete_redirect_link($_POST['delete_redirect_link']);
		}
		?>			
			<form method="post" action="" id="redirect_links_form">
				<input type="hidden" value="" name="delete_redirect_link" id="delete_redirect_link" />
				<div class="ihc-stuffbox">
					<h3><?php _e('Redirect Links', 'ihc');?></h3>
					<div class="inside">
						<div class="iump-form-line">
							<label class="iump-labels-special"><?php _e('Name:', 'ihc');?></label>
							<input type="text" value="" name="name" style="width: 500px;"/>				
						</div>
						<div class="iump-form-line">
							<label class="iump-labels-special"><?php _e('Link:', 'ihc');?></label>
							<input type="text" value="" name="url" style="width: 500px;"/>				
						</div>
						
						<div style="margin-top: 15px;">
							<input type="submit" value="<?php _e('Add New', 'ihc');?>" name="ihc_save" class="button button-primary button-large" />
						</div>	
					</div>
				</div>
			</form>
		<?php 
		$data = get_option('ihc_custom_redirect_links_array');
		if ($data && count($data)){
			?>
							<div class="ihc-dashboard-form-wrap">
							<table class="wp-list-table widefat fixed tags" style="margin-bottom: 20px;">
								<thead>
								<tr>
									<th class="manage-column"><?php _e('Name', 'ihc');?></th>
									<th class="manage-column"><?php _e('Link', 'ihc');?></th>
									<th class="manage-column" style="width:80px;  text-align: center;"><?php _e('Delete', 'ihc');?></th>
								</tr>
								</thead>
							<?php 
								$i = 1;
								foreach ($data as $key=>$url){
								?>
								<tr class="<?php if ($i%2==0) echo 'alternate';?>">
									<td><?php echo $key;?></td>
									<td><?php echo $url;?></td>
									<td align="center">
										<i class="fa-ihc ihc-icon-remove-e" style="cursor:pointer;" onClick="jQuery('#delete_redirect_link').val('<?php echo $key;?>');jQuery('#redirect_links_form').submit();"></i>
									</td>
								</tr>
								<?php 
								}
								?>
							</table>
							</div>
							<?php 
						}
		break;
		
		case 'double_email_verification':
			ihc_save_update_metas('double_email_verification');//save update metas
			$meta_arr = ihc_return_meta_arr('double_email_verification');//getting metas
			echo ihc_check_default_pages_set();//set default pages message	
			echo ihc_check_payment_gateways();
			$pages = $pages + ihc_get_redirect_links_as_arr_for_select();
			?>
			<form action="" method="post">
				<div class="ihc-stuffbox">
					<h3><?php _e('Double E-mail Verification', 'ihc');?></h3>
					<div class="inside">		
						<div class="iump-form-line">
							<label class="iump-labels-special"><?php _e('Activation Link Expire Time:', 'ihc');?></label>
							<select name="ihc_double_email_expire_time">
								<?php 
									$arr = array(
															'-1' => 'Never',
															'900' => '15 Minutes',
															'3600' => '1 Hour',
															'43200' => '12 Hours',
															'86400' => '1 Day',
															);
									foreach ($arr as $k=>$v){
										?>
										<option value="<?php echo $k?>" <?php if ($k==$meta_arr['ihc_double_email_expire_time']) echo 'selected';?> >
											<?php echo $v;?>
										</option>
										<?php 
									}
								?>
							</select>	
							<div class="ihc-dashboard-basic-info-label"><?php _e('', 'ihc');?></div>			
						</div>
						
						<div class="iump-form-line">
							<label class="iump-labels-special"><?php _e('Success Redirect:', 'ihc');?></label>
							<select name="ihc_double_email_redirect_success">
								<option value="-1" <?php if($meta_arr['ihc_double_email_redirect_success']==-1)echo 'selected';?> >...</option>
								<?php 
									if ($pages){
										foreach ($pages as $k=>$v){
											?>
												<option value="<?php echo $k;?>" <?php if($meta_arr['ihc_double_email_redirect_success']==$k)echo 'selected';?> ><?php echo $v;?></option>
											<?php 
										}						
									}
								?>
							</select>
						</div>
						
						<div class="iump-form-line">
							<label class="iump-labels-special"><?php _e('Error Redirect:', 'ihc');?></label>
							<select name="ihc_double_email_redirect_error">
								<option value="-1" <?php if($meta_arr['ihc_double_email_redirect_error']==-1)echo 'selected';?> >...</option>
								<?php 
									if ($pages){
										foreach ($pages as $k=>$v){
											?>
												<option value="<?php echo $k;?>" <?php if($meta_arr['ihc_double_email_redirect_error']==$k)echo 'selected';?> ><?php echo $v;?></option>
											<?php 
										}						
									}
								?>
							</select>
						</div>						
						
						<div class="iump-form-line">
							<label class="iump-labels-special"><?php _e('Delete User if not verified:', 'ihc');?></label>
							<select name="ihc_double_email_delete_user_not_verified">
								<?php 
									$arr = array(
															'-1' => 'Never',
															'1' => 'After 1 day',
															'7' => 'After 7 days',
															'14' => 'After 14 days',
															'30' => 'After 30 days',
															);
									foreach ($arr as $k=>$v){
										?>
										<option value="<?php echo $k?>" <?php if ($k==$meta_arr['ihc_double_email_delete_user_not_verified']) echo 'selected';?> >
											<?php echo $v;?>
										</option>
										<?php 
									}
								?>
							</select>
						</div>	
												
						<div class="ihc-wrapp-submit-bttn iump-submit-form">
							<input type="submit" value="<?php _e('Save', 'ihc');?>" name="ihc_save" class="button button-primary button-large" />
						</div>	
					</div>
				</div>
			</form>			
			<?php 
			break;
	case 'custom_currency':
		if (!empty($_POST['new_currency_code']) && !empty($_POST['new_currency_name'])){
			$data = get_option('ihc_currencies_list');
			if (empty($data[$_POST['new_currency_code']])){
				$data[$_POST['new_currency_code']] = $_POST['new_currency_name'];	
			}
			update_option('ihc_currencies_list', $data);
		}
		$basic_currencies = ihc_get_currencies_list('custom');
		?>
			<form action="" method="post">
				<div class="ihc-stuffbox">
					<h3><?php _e('Add new Currency', 'ihc');?></h3>
					<div class="inside">		
						<div class="iump-form-line">
							<label class="iump-labels-special"><?php _e('Code:', 'ihc');?></label>
							<input type="test" value="" name="new_currency_code" />
							<p><?php _e('Insert a valid Currency Code, ex: ', 'ihc');?><span style="font-weight:bold;"><?php _e('USD, EUR, CAD.', 'ihc');?></span></p>
						</div>
						<div class="iump-form-line">
							<label class="iump-labels-special"><?php _e('Name:', 'ihc');?></label>
							<input type="test" value="" name="new_currency_name" />
						</div>
						<div class="ihc-wrapp-submit-bttn iump-submit-form">
							<input type="submit" value="<?php _e('Save', 'ihc');?>" name="ihc_save" class="button button-primary button-large" />
						</div>							
					</div>
				</div>
				
				<?php 
				if ($basic_currencies!==FALSE && count($basic_currencies)>0){
				?>
				<div class="ihc-dashboard-form-wrap">
					<table class="wp-list-table widefat fixed tags" style="margin-bottom: 20px;">
						<thead>
							<tr>
								<th class="manage-column">Code</th>
								<th class="manage-column">Name</th>
								<th class="manage-column" style="width:80px; text-align: center;">Delete</th>
							</tr>
						</thead>
						<tbody>
							<?php 
							foreach ($basic_currencies as $code=>$name){
							?>
							<tr id="ihc_div_<?php echo $code;?>">
								<td><?php echo $code;?></td>
								<td><?php echo $name;?></td>
								<td style="text-align: center;"><i class="fa-ihc ihc-icon-remove-e" onClick="ihc_remove_currency('<?php echo $code;?>');" style="cursor: pointer;"></i></td>
							</tr>						
							<?php 
							}
							?>
	
						</tbody>
					</table>				
				</div>
				<?php }?>			
			</form>
		<?php 
		break;
}


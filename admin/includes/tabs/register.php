<?php 
include_once IHC_PATH . 'admin/includes/functions/register.php';
?>
<div class="ihc-subtab-menu">
	<a class="ihc-subtab-menu-item" href="<?php echo $url.'&tab='.$tab.'&subtab=settings';?>"><?php _e('Register Showcase', 'ihc');?></a>
	<a class="ihc-subtab-menu-item" href="<?php echo $url.'&tab='.$tab.'&subtab=msg';?>"><?php _e('Custom Messages', 'ihc');?></a>
	<a class="ihc-subtab-menu-item" href="<?php echo $url.'&tab='.$tab.'&subtab=custom_fields';?>"><?php _e('Custom Fields', 'ihc');?></a>
</div>
<?php
echo ihc_check_default_pages_set();//set default pages message
echo ihc_check_payment_gateways();

if (isset($_REQUEST['subtab'])) {
	$subtab = $_REQUEST['subtab'];
} else {
	$subtab = 'settings';
}

switch ($subtab){
	case 'settings':
		ihc_save_update_metas('register');//save update metas
		$meta_arr = ihc_return_meta_arr('register');//getting metas
		?>
		<div class="iump-page-title">Ultimate Membership Pro - 
							<span class="second-text">
								<?php _e('Register Form', 'ihc');?>
							</span>
						</div>
			<div class="ihc-stuffbox">
				<div class="impu-shortcode-display">
					[ihc-register]
				</div>
			</div>		
			<form action="" method="post">
				<div class="ihc-stuffbox">
					<h3><?php _e('Design', 'ihc');?></h3>
					<div class="inside">
						<div class="iump-register-select-template">
							<?php 
								$templates = array('ihc-register-1'=>__('Template', 'ihc').' 1', 'ihc-register-2'=>__('Template', 'ihc').' 2', 'ihc-register-3'=>__('Template', 'ihc').' 3',
													'ihc-register-4'=>__('Template', 'ihc').' 4', 'ihc-register-5'=>__('Template', 'ihc').' 5', 'ihc-register-6'=>__('Template', 'ihc').' 6', 'ihc-register-7'=>__('Template', 'ihc').' 7');
							?>
							<?php _e('Register Template:', 'ihc');?>
							<select name="ihc_register_template" id="ihc_register_template" onChange="ihcRegisterLockerPreview();" style="min-width:400px">
							<?php 
								foreach ($templates as $k=>$v){
								?>
									<option value="<?php echo $k;?>" <?php if ($k==$meta_arr['ihc_register_template'])echo 'selected';?> >
										<?php echo $v;?>
									</option>
								<?php 	
								}
							?>
							</select>						
						</div>
										
						<div style="padding: 5px;">
							<div id="register_preview"></div>
						</div>
						
						<div style="margin-top: 15px;">
							<input type="submit" value="<?php _e('Save', 'ihc');?>" name="ihc_save" class="button button-primary button-large" />
						</div>	
										
					</div>
				</div>
						
				<div class="ihc-stuffbox">
					<h3><?php _e('Settings', 'ihc');?></h3>
					<div class="inside">
						<div class="iump-form-line">
						
								<h2><?php _e('Level Settings', 'ihc');?></h2>
							<div style="font-weight:bold"><?php _e('Choose Subscription Type:', 'ihc');?></div>
							<select name="ihc_subscription_type" onClick="ihc_select_sh_div(this, '#level_assign_to_user', 'predifined_level');">
								<option value="predifined_level" <?php if ('predifined_level'==$meta_arr['ihc_subscription_type']) echo 'selected';?> ><?php _e('Predifined Level', 'ihc');?></option>
								<option value="subscription_plan" <?php if ('subscription_plan'==$meta_arr['ihc_subscription_type']) echo 'selected';?> ><?php _e('Subscription Plan', 'ihc');?></option>
							</select>
							<div  class="iump-form-line" id="level_assign_to_user" style="padding:0;border-bottom: none; margin-top:10px; <?php if($meta_arr['ihc_subscription_type']=='predifined_level') echo 'display: block;'; else echo 'display: none;';?>" >
								<div style="font-weight:bold"><?php _e('Level assign to new user', 'ihc');?></div>
								<select name="ihc_register_new_user_level">
									<option value="-1" <?php if($meta_arr['ihc_register_new_user_level']==-1)echo 'selected';?> ><?php _e('None', 'ihc');?></option>
									<?php 
										$levels = get_option('ihc_levels');
										if ($levels && count($levels)){
											foreach ($levels as $id=>$v){
												?>
													<option value="<?php echo $id;?>" <?php if ($meta_arr['ihc_register_new_user_level']==$id) echo 'selected';?> ><?php echo $v['name'];?></option>
												<?php 
											}
										}
									?>
								</select>						
							</div>
							
							<p><?php _e('If "Subscription Plan" is selected, the user is redirected to Subscription Plan Page to choose a Level. Be sure that the Subscription Plan page is properly set.', 'ihc');?></p>	
						</div>
						<div  class="iump-form-line">						
								<h2><?php _e('WP Role', 'ihc');?></h2>
								<div style="font-weight:bold"><?php _e('Predefined Wordpress Role Assign to new users:', 'ihc');?></div>
								<select name="ihc_register_new_user_role">
									<?php 
										$roles = ihc_get_wp_roles_list();
										if ($roles){
											foreach ($roles as $k=>$v){
												$selected = ($meta_arr['ihc_register_new_user_role']==$k) ? 'selected' : '';
												?>
													<option value="<?php echo $k;?>" <?php echo $selected;?> ><?php echo $v;?></option>
												<?php 
											}	
										}
									?>
								</select>
								<p><?php _e('If the "Pending" Role is set, the user can not login until the Admin manually Approve the user.', 'ihc');?></p>						
						

							<label class="iump_label_shiwtch" style="margin:10px 0 10px -10px;">
								<?php $checked = ($meta_arr['ihc_automatically_switch_role']) ? 'checked' : '';?>
								<input type="checkbox" class="iump-switch" onClick="iump_check_and_h(this, '#ihc_automatically_switch_role');" <?php echo $checked;?> />
								<div class="switch" style="display:inline-block;"></div>
							</label>
							<?php _e("Automatically Switch Role when the Payment is completed", 'ihc');?>
							<input type="hidden" name="ihc_automatically_switch_role" value="<?php echo $meta_arr['ihc_automatically_switch_role'];?>" id="ihc_automatically_switch_role" /> 								
							<div style="font-weight: bold; margin-top: 5px;"><?php _e("New Role after payment:", 'ihc');?></div>
								<select name="ihc_automatically_new_role">
									<?php 
										if ($roles){
											unset($roles['pending_user']);
											foreach ($roles as $k=>$v){
												$selected = ($meta_arr['ihc_automatically_new_role']==$k) ? 'selected' : '';
												?>
													<option value="<?php echo $k;?>" <?php echo $selected;?> ><?php echo $v;?></option>
												<?php 
											}	
										}
									?>
								</select>							
						</div>
													
						<div class="iump-form-line">
								
								<h2><?php _e('Form Settings', 'ihc');?></h2>
								<div style="font-weight:bold"><?php _e('Password Minimum Length', 'ihc');?></div>
								<input type="number" value="<?php echo $meta_arr['ihc_register_pass_min_length'];?>" name="ihc_register_pass_min_length" min="4"/>				
							
							<div style="margin-top:15px;">
								<div style="font-weight:bold"><?php _e('Password Strength Options', 'ihc');?></div>
								<select name="ihc_register_pass_options">
									<option value="1" <?php if ($meta_arr['ihc_register_pass_options']==1)echo 'selected';?> ><?php _e('Standard', 'ihc');?></option>
									<option value="2" <?php if ($meta_arr['ihc_register_pass_options']==2)echo 'selected';?> ><?php _e('Characters and digits', 'ihc');?></option>
									<option value="3" <?php if ($meta_arr['ihc_register_pass_options']==3)echo 'selected';?> ><?php _e('Characters, digits, minimum one uppercase letter', 'ihc');?></option>
								</select>
							</div>			
						</div>	
						<div class="iump-form-line">
						<h2><?php _e('Admin Notification', 'ihc');?></h2>
							<label class="iump_label_shiwtch" style="margin:10px 0 10px -10px;">
								<?php $checked = ($meta_arr['ihc_register_admin_notify']) ? 'checked' : '';?>
								<input type="checkbox" class="iump-switch" onClick="iump_check_and_h(this, '#ihc_register_admin_notify');" <?php echo $checked;?> />
								<div class="switch" style="display:inline-block;"></div>
							</label>
							<input type="hidden" name="ihc_register_admin_notify" value="<?php echo $meta_arr['ihc_register_admin_notify'];?>" id="ihc_register_admin_notify" /> 				
							<?php _e('Notify admin address on every new registration', 'ihc');?>
							<p><?php _e('When a new user has registered, the WP Admin is notified using the default Email Admin address set into current WordPress Instance', 'ihc');?></p>
						</div>	
						
						<div class="iump-form-line">
							<h2><?php _e('Opt-In Subscription', 'ihc');?></h2>
							
								<label class="iump_label_shiwtch" style="margin:10px 0 10px -10px;">
									<?php $checked = ($meta_arr['ihc_register_opt-in']) ? 'checked' : '';?>
									<input type="checkbox" class="iump-switch" onClick="iump_check_and_h(this, '#ihc_register_opt-in');" <?php echo $checked;?> />
									<div class="switch" style="display:inline-block;"></div>
								</label>
								<input type="hidden" name="ihc_register_opt-in" value="<?php echo $meta_arr['ihc_register_opt-in'];?>" id="ihc_register_opt-in" /> 	
								<?php _e('Enable Opt-In', 'ihc');?>		
								<div style="margin-top:10px;">
								<div style="font-weight: bold;"><?php _e('Opt-In Destination:', 'ihc');?></div>
                                <select name="ihc_register_opt-in-type">
                                    <?php
                                        $subscribe_types = array(
                                                                    'aweber' => 'AWeber',
                                                                    'campaign_monitor' => 'CampaignMonitor',
                                                                    'constant_contact' => 'Constant Contact',
                                                                    'email_list' => __('E-mail List', 'ihc'),
                                                                    'get_response' => 'GetResponse',
                                                                    'icontact' => 'IContact',
                                                                    'madmimi' => 'Mad Mimi',
                                                                    'mailchimp' => 'MailChimp',
                                                                    'mymail' => 'MyMail',
                                                                    'wysija' => 'Wysija',
                                                                 );
                                        foreach ($subscribe_types as $k=>$v){
                                            $selected = ($meta_arr['ihc_register_opt-in-type']==$k) ? 'selected' : '';
                                            ?>
                                                <option value="<?php echo $k;?>" <?php echo $selected;?> ><?php 
                                                	echo $v;
                                                ?></option>
                                            <?php
                                        }
                                    ?>
                                </select>
							</div>					
							<p><?php _e('The User email address is sent to your OptIn Destination', 'ihc');?></p>
						</div>
						<div class="iump-form-line">
							<h2><?php _e('Double Email Verification', 'ihc');?></h2>
							<label class="iump_label_shiwtch" style="margin:10px 0 10px -10px;">
									<?php $checked = ($meta_arr['ihc_register_double_email_verification']) ? 'checked' : '';?>
									<input type="checkbox" class="iump-switch" onClick="iump_check_and_h(this, '#ihc_register_double_email_verification');" <?php echo $checked;?> />
									<div class="switch" style="display:inline-block;"></div>
								</label>
								<input type="hidden" name="ihc_register_double_email_verification" value="<?php echo $meta_arr['ihc_register_double_email_verification'];?>" id="ihc_register_double_email_verification" /> 	
								<?php _e('Double E-mail Verification', 'ihc');?>
												
							<p><?php _e('Be sure that your Notifications for Double Email verification are properly set. Also, check the Settings for this module from General Options tab.', 'ihc');?> <a href="admin.php?page=ihc_manage&tab=general&subtab=double_email_verification" target="_blank">here</a></p>	
						</div>
						<div class="iump-form-line">	
							<h2><?php _e('Other Settings', 'ihc');?></h2>
							<div style="margin-bottom: 15px;">							
								<label class="iump_label_shiwtch" style="margin:10px 0 10px -10px;">
									<?php $checked = ($meta_arr['ihc_register_show_level_price']) ? 'checked' : '';?>
									<input type="checkbox" class="iump-switch" onClick="iump_check_and_h(this, '#ihc_register_show_level_price');" <?php echo $checked;?> />
									<div class="switch" style="display:inline-block;"></div>
								</label>
								<input type="hidden" name="ihc_register_show_level_price" value="<?php echo $meta_arr['ihc_register_show_level_price'];?>" id="ihc_register_show_level_price" /> 	
								<?php _e('Show Level Price & Data On Register Form', 'ihc');?>							
							</div>

							<div style="margin-bottom: 15px;">							
								<label class="iump_label_shiwtch" style="margin:10px 0 10px -10px;">
									<?php $checked = ($meta_arr['ihc_register_auto_login']) ? 'checked' : '';?>
									<input type="checkbox" class="iump-switch" onClick="iump_check_and_h(this, '#ihc_register_auto_login');" <?php echo $checked;?> />
									<div class="switch" style="display:inline-block;"></div>
								</label>
								<input type="hidden" name="ihc_register_auto_login" value="<?php echo $meta_arr['ihc_register_auto_login'];?>" id="ihc_register_auto_login" /> 	
								<?php _e('Auto Login after Registration', 'ihc');?>							
							</div>

						</div>
													
						<div style="margin-top: 15px;">
							<input type="submit" value="<?php _e('Save', 'ihc');?>" name="ihc_save" class="button button-primary button-large" />
						</div>			
					</div>
				</div>	
		
				<div class="ihc-stuffbox">
					<h3><?php _e('Terms & Conditions (TOS) Label', 'ihc');?></h3>
					<div class="inside">
					  <div  class="iump-form-line">
						<input type="text" name="ihc_register_terms_c" value="<?php echo ihc_correct_text($meta_arr['ihc_register_terms_c']);?>" style="min-width:350px"/>
					  </div>	
						<div style="margin-top: 15px;">
							<input type="submit" value="<?php _e('Save', 'ihc');?>" name="ihc_save" onClick="" class="button button-primary button-large" />
						</div>					
					</div>
				</div>
				
				<div class="ihc-stuffbox">
					<h3><?php _e('Custom CSS:', 'ihc');?></h3>
					<div class="inside">		
						<div>
							<textarea name="ihc_register_custom_css" id="ihc_register_custom_css" class="ihc-dashboard-textarea" onBlur="ihcRegisterLockerPreview();"><?php 
							echo $meta_arr['ihc_register_custom_css'];
							?></textarea>
						</div>
						<div style="margin-top: 15px;">
							<input type="submit" value="<?php _e('Save', 'ihc');?>" name="ihc_save" class="button button-primary button-large" />
						</div>					
					</div>
				</div>	
								
			</form>
			<script>
				jQuery(document).ready(function(){
					ihcRegisterLockerPreview();
				});
			</script>					
		<?php 			
	break;
	case 'msg':
		ihc_save_update_metas('register-msg');//save update metas
		$meta_arr = ihc_return_meta_arr('register-msg');//getting metas
		?>
			<form method="post" action="">
				<div class="ihc-stuffbox">
					<h3><?php _e('Custom Messages', 'ihc');?></h3>
					<div class="inside">	
						
						<div style="display:inline-block;width: 45%;">
							<div>
								<div class="iump-labels-special"><?php _e('Error - Username is taken:', 'ihc');?></div>
								<textarea name="ihc_register_username_taken_msg" class="ihc-dashboard-textarea"><?php echo ihc_correct_text($meta_arr['ihc_register_username_taken_msg']);?></textarea>
							</div>
							
							<div>
								<div class="iump-labels-special"><?php _e('Error - Invalid Username:', 'ihc');?></div>
								<textarea name="ihc_register_error_username_msg" class="ihc-dashboard-textarea"><?php echo ihc_correct_text($meta_arr['ihc_register_error_username_msg']);?></textarea>
							</div>		
										
							<div>
								<div class="iump-labels-special"><?php _e('Error - Email is taken:', 'ihc');?></div>
								<textarea name="ihc_register_email_is_taken_msg" class="ihc-dashboard-textarea"><?php echo ihc_correct_text($meta_arr['ihc_register_email_is_taken_msg']);?></textarea>
							</div>
							
							<div>
								<div class="iump-labels-special"><?php _e('Error - Invalid Email Address:', 'ihc');?></div>
								<textarea name="ihc_register_invalid_email_msg" class="ihc-dashboard-textarea"><?php echo ihc_correct_text($meta_arr['ihc_register_invalid_email_msg']);?></textarea>
							</div>
							
							<div>
								<div class="iump-labels-special"><?php _e('Error - Email Addresses did not Match:', 'ihc');?></div>
								<textarea name="ihc_register_emails_not_match_msg" class="ihc-dashboard-textarea"><?php echo ihc_correct_text($meta_arr['ihc_register_emails_not_match_msg']);?></textarea>
							</div>
											
							<div>
								<div class="iump-labels-special"><?php _e('Error - Password did not match:', 'ihc');?></div>
								<textarea name="ihc_register_pass_not_match_msg" class="ihc-dashboard-textarea"><?php echo ihc_correct_text($meta_arr['ihc_register_pass_not_match_msg']);?></textarea>
							</div>	
							
							<div>
								<div class="iump-labels-special"><?php _e('Error - Password Only Characters and Digits:', 'ihc');?></div>
								<textarea name="ihc_register_pass_letter_digits_msg" class="ihc-dashboard-textarea"><?php echo ihc_correct_text($meta_arr['ihc_register_pass_letter_digits_msg']);?></textarea>
							</div>							
						</div>
						
						<div style="display:inline-block;width: 45%;vertical-align:top;">
							<div>
								<div class="iump-labels-special"><?php _e('Error - Password Min Length:', 'ihc');?></div>
								<textarea name="ihc_register_pass_min_char_msg" class="ihc-dashboard-textarea"><?php echo ihc_correct_text($meta_arr['ihc_register_pass_min_char_msg']);?></textarea>
								<div class="ihc-dashboard-mini-msg-alert"><?php _e('Where {X} will be the minimum length of password.', 'ihc');?></div>
							</div>								
							
							<div>
								<div class="iump-labels-special"><?php _e('Error - Password Characters, Digits and minimum one uppercase letter:', 'ihc');?></div>
								<textarea name="ihc_register_pass_let_dig_up_let_msg" class="ihc-dashboard-textarea"><?php echo ihc_correct_text($meta_arr['ihc_register_pass_let_dig_up_let_msg']);?></textarea>
							</div>	
											
							<div>
								<div class="iump-labels-special"><?php _e('Error - Pending User:', 'ihc');?></div>
								<textarea name="ihc_register_pending_user_msg" class="ihc-dashboard-textarea"><?php echo ihc_correct_text($meta_arr['ihc_register_pending_user_msg']);?></textarea>
							</div>	
							
							<div>
								<div class="iump-labels-special"><?php _e('Error - Empty Required Fields:', 'ihc');?></div>
								<textarea name="ihc_register_err_req_fields" class="ihc-dashboard-textarea"><?php echo ihc_correct_text($meta_arr['ihc_register_err_req_fields']);?></textarea>
							</div>
							
							<div>
								<div class="iump-labels-special"><?php _e('Error - ReCaptcha:', 'ihc');?></div>
								<textarea name="ihc_register_err_recaptcha" class="ihc-dashboard-textarea"><?php echo ihc_correct_text($meta_arr['ihc_register_err_recaptcha']);?></textarea>
							</div>		
			
							<div>
								<div class="iump-labels-special"><?php _e('Error - TOS:', 'ihc');?></div>
								<textarea name="ihc_register_err_tos" class="ihc-dashboard-textarea"><?php echo ihc_correct_text($meta_arr['ihc_register_err_tos']);?></textarea>
							</div>					
							
							<div>
								<div class="iump-labels-special"><?php _e('Success Message:', 'ihc');?></div>
								<textarea name="ihc_register_success_meg" class="ihc-dashboard-textarea"><?php echo ihc_correct_text($meta_arr['ihc_register_success_meg']);?></textarea>
							</div>													
						</div>	
									
						<div style="margin-top: 15px;">
							<input type="submit" value="<?php _e('Save', 'ihc');?>" name="ihc_save" onClick="" class="button button-primary button-large" />
						</div>					
					</div>
				</div>							
			</form>		
		<?php 		
	break;
	case 'custom_fields':
		//SAVE/UPDATE
		if (isset($_GET['delete_field_id']) && $_GET['delete_field_id']!=''){
			ihc_delete_user_field($_GET['delete_field_id']);//delete user custom fields
		} 
		if (isset($_POST['ihc_add_edit_cf'])){
			ihc_save_user_field($_POST);//save update user custom fields
		}
		if (isset($_POST['ihc_save_custom_field']) ){
			ihc_update_reg_fields($_POST);//update register fields
		}
		if (isset($_POST['ihc_update_register_fields']) && isset($_POST['id'])){
			ihc_update_register_fields($_POST);//update the name, labels
		}	
		
		//GETTING METAS
		$reg_fields = ihc_get_user_reg_fields();
		ksort($reg_fields);

		$the_levels = get_option('ihc_levels');
		if ($the_levels){
			foreach ($the_levels as $k=>$v){
				$levels_arr[$k] = $v['name'];
			}
			unset($the_levels);
			unset($k);
			unset($v);
		}
		
		?>
			<div class="clear"></div>
			<a href="<?php echo $url.'&tab=register&subtab=add_edit_cf';?>" class="indeed-add-new-like-wp" style="display: inline-block; margin: 10px 0px;">
				<?php _e('Add New Register Form Field', 'ihc');?>
			</a>	
			<div class="clear"></div>
			<form action="" method="post">
				<div class="ihc-stuffbox">
					<h3><?php _e('Registration form fields', 'ihc');?></h3>
					<div class="inside">
						<div style="margin-bottom: 15px;">
							<input type="submit" value="<?php _e('Save', 'ihc');?>" name="ihc_save_custom_field" class="button button-primary button-large" />
						</div>
								<div class="ihc-sortable-table-wrapp">

									<table class="wp-list-table widefat fixed tags" id='ihc-register-fields-table'>
										    <thead>
												<tr>
													<th class="manage-column"><?php _e('Slug', 'ihc');?></th>
													<th class="manage-column"><?php _e('Label', 'ihc');?></th>
													<th class="manage-column"><?php _e('Field Type', 'ihc');?></th>
													<th class="manage-column"><?php _e('On Admin', 'ihc');?></th>
													<th class="manage-column"><?php _e('On Register Page', 'ihc');?></th>
													<th class="manage-column"><?php _e('On Account Page', 'ihc');?></th>
													<th class="manage-column"><?php _e("Targeting Levels", 'ihc');?></th>
													<th class="manage-column"><?php _e('Required', 'ihc');?></th>
													<th class="manage-column"><?php _e('WP Native', 'ihc');?></th>
													<th class="manage-column" style="width: 25px;"><?php _e('Edit', 'ihc');?></th>
													<th class="manage-column" style="width: 35px;"><?php _e('Delete', 'ihc');?></th>
												</tr>
											</thead>
											<tfoot>
												<tr>
													<th class="manage-column"><?php _e('Slug', 'ihc');?></th>
													<th class="manage-column"><?php _e('Label', 'ihc');?></th>
													<th class="manage-column"><?php _e('Field Type', 'ihc');?></th>
													<th class="manage-column"><?php _e('On Admin', 'ihc');?></th>
													<th class="manage-column"><?php _e('On Register Page', 'ihc');?></th>
													<th class="manage-column"><?php _e('On Account Page', 'ihc');?></th>
													<th class="manage-column"><?php _e("Targeting Levels", 'ihc');?></th>
													<th class="manage-column"><?php _e('Required', 'ihc');?></th>
													<th class="manage-column"><?php _e('WP Native', 'ihc');?></th>
													<th class="manage-column" style="width: 25px;"><?php _e('Edit', 'ihc');?></th>
													<th class="manage-column" style="width: 35px;"><?php _e('Delete', 'ihc');?></th>
												</tr>									
											</tfoot>	
											<tbody>
									<?php 							
									foreach ($reg_fields as $k=>$v){
											
										switch ($v['name']){
											case 'ihc_social_media':
												$tr_extra_class = "ihc-social-media-tr-dashboard";
												break;
											case 'ihc_coupon':
												$tr_extra_class = "ihc-coupon-tr-dashboard";
												break;
											case 'ihc_avatar':
												$tr_extra_class = "ihc-avatar-tr-dashboard";
												break;
											case 'payment_select':
												$tr_extra_class = "ihc-payment-tr-dashboard";
												break;
											default:
												$tr_extra_class = '';
												break;
										}																				
										?>
										<tr class="<?php echo $tr_extra_class;?>" id="tr_<?php echo $k;?>">
											<td>
												<?php echo $v['name'];?>
												<input type="hidden" value="<?php echo $k;?>" name="ihc-order-<?php echo $k;?>" class="ihc-order" />	
											</td>
											<td><?php 
													if ($v['native_wp']){
														_e($v['label'], 'ihc');
													} else {
														echo $v['label'];
													}													
												?>
											</td>
											<td><?php echo $v['type'];?></td>
											<td>
												<?php 
													if($v['name']=='ihc_social_media' || $v['name']=='payment_select'){
														echo '-';
													} else if ($v['display_admin']==2){
														_e('Always', 'ihc');
													} else {
														?>
														<input type="checkbox" onClick="check_and_h(this, '#ihc-field-display-admin<?php echo $k;?>');inc_req(this, <?php echo $k;?>);" <?php if($v['display_admin']) echo 'checked';?> />
														<input type="hidden" value="<?php echo $v['display_admin'];?>" name="ihc-field-display-admin<?php echo $k;?>" id="ihc-field-display-admin<?php echo $k;?>" />
														<?php 
													}
												?>
											</td>
											<td>
												<?php 
													if ($v['display_public_reg']==2){
														_e('Always', 'ihc');
													} else {
														?>
														<input type="checkbox" onClick="check_and_h(this, '#ihc-field-display-public-reg<?php echo $k;?>');inc_req(this, <?php echo $k;?>);" <?php if($v['display_public_reg']) echo 'checked';?> />
														<input type="hidden" value="<?php echo $v['display_public_reg'];?>" name="ihc-field-display-public-reg<?php echo $k;?>" id="ihc-field-display-public-reg<?php echo $k;?>" />
														<?php 
													}
												?>
											</td>
											<td>
												<?php 
													if ($v['display_public_ap']==2){
														_e('Always', 'ihc');
													} else if($v['name']=='ihc_social_media'){
														echo '-';
													} else {
														?>
														<input type="checkbox" onClick="check_and_h(this, '#ihc-field-display-public-ap<?php echo $k;?>');inc_req(this, <?php echo $k;?>);" <?php if($v['display_public_ap']) echo 'checked';?> />
														<input type="hidden" value="<?php echo $v['display_public_ap'];?>" name="ihc-field-display-public-ap<?php echo $k;?>" id="ihc-field-display-public-ap<?php echo $k;?>" />
														<?php 
													}
												?>
											</td>
											<td><?php 
												if (isset($v['target_levels']) && $v['target_levels']!=''){
													$target_levels = explode(',', $v['target_levels']);
													foreach ($target_levels as $target_value){
														if ($target_value==-1){
															echo '<div class="ihc-register-dashboard-level-targeting">' . __('No level selected', 'ihc') . '</div>';
														} else {
															echo '<div class="ihc-register-dashboard-level-targeting-l">' . $levels_arr[$target_value] . '</div>';	
														}
													}
													unset($target_levels);
												} else {
													echo '<div class="ihc-register-dashboard-level-targeting">' . __('All', 'ihc') . '</div>';	
												}
											?></td>																						
											<td>
												<?php 
													if ($v['display_public_reg']==2){
														_e('Always', 'ihc');
													} else if ($v['req']==2){
														_e('Required When Selected', 'ihc');
													} else if ($v['name']=='ihc_social_media'){
														echo '-';
													} else {
														?>
														<input type="checkbox" onClick="check_and_h(this, '#ihc-require-<?php echo $k;?>');" <?php if ($v['req']) echo 'checked';?> id="req-check-<?php echo $k;?>"/>
														<input type="hidden" value="<?php echo $v['req'];?>" name="ihc-require-<?php echo $k;?>" id="ihc-require-<?php echo $k;?>" />												
														<?php 	
													}
												?>
											</td>
											<td>
												<?php 
													if ($v['native_wp']){
														_e('Yes', 'ihc');
													} else {
														_e('No', 'ihc');
													}
												?>
											</td>
											<td>
												<?php 
													$no_edit = array('ihc_social_media');
													if($v['native_wp'] || in_array($v['name'], $no_edit) ){
														echo '-';
													} else {
														?>
														<a href="<?php echo $url.'&tab=register&subtab=add_edit_cf&id='.$k;?>">
															<i class="fa-ihc ihc-icon-edit-e"></i>
														</a>
														<?php 	
													}
												?>											
											</td>
											<td>
												<?php 
													$no_delete_fields = array('ihc_avatar', 'recaptcha', 'ihc_coupon', 'tos', 'ihc_social_media');
													if ($v['native_wp'] || in_array($v['name'], $no_delete_fields)){
														echo '-';
													} else {
														?>
															<a href="<?php echo $url.'&tab=register&subtab=custom_fields&delete_field_id='.$k;?>">
																<i class="fa-ihc ihc-icon-remove-e"></i>
															</a>												
														<?php 	
													}
												?>
											</td>
										</tr>
										<?php 		
										}
									?>
										</tbody>
									</table>
								</div>	
						<div style="margin-top: 15px;">
							<input type="submit" value="<?php _e('Save', 'ihc');?>" name="ihc_save_custom_field" class="button button-primary button-large" />
						</div>	
					</div>
				</div>
			</form>		
		<?php 	
	break;
	case 'add_edit_cf':
		$meta = get_option('ihc_user_fields');
		if (isset($_REQUEST['id']) && isset($meta[$_REQUEST['id']]) && count($meta[$_REQUEST['id']])){
			$meta_arr = $meta[$_REQUEST['id']];
			$bttn = 'ihc_update_register_fields';
		} else {
			$meta_arr = array(  'name' => '',
							    'label' => '', 
							    'type' => 'text',
								'values' => '',
								'sublabel' => '',
								'class' => '',
					);	
			$bttn = 'ihc_add_edit_cf';
		}
		$disabled = '';
		if ($meta_arr['name']=='confirm_email' || $meta_arr['name']=='tos' || $meta_arr['name']=='recaptcha' 
			|| $meta_arr['name']=='ihc_avatar' || $meta_arr['name']=='ihc_coupon' || $meta_arr['name']=='payment_select'){
			$disabled = 'disabled';
		}
		?>
			<form method="post" action="<?php echo $url.'&tab=register&subtab=custom_fields';?>">
				<div class="ihc-stuffbox">
					<h3><?php _e('User Custom Fields', 'ihc');?></h3>
					<div class="inside">
						<?php 
							if (isset($_REQUEST['id'])){
								?>
								<input type="hidden" name="id" value="<?php echo $_REQUEST['id'];?>" />
								<?php 
							}
						?>
						<div class="iump-form-line">
							<label class="iump-labels"><?php _e('Slug:', 'ihc');?> </label> <input type="text" name="name" value="<?php echo $meta_arr['name'];?>" <?php echo $disabled;?>/>
						</div>
						<div class="iump-form-line  iump-no-border">
							<label class="iump-labels" style="font-weight:bold;"><?php _e('Field Type:', 'ihc');?></label>
							<select id="ihc_new_field-type" <?php if ($disabled) echo 'disabled'; else echo 'name="type"';?> onChange="ihc_register_fields(this.value);">
								<?php 
									$field_types = array('text'=>'Text', 
															'textarea'=>'Textarea', 
															'date'=>'Date Picker', 
															'number'=>'Number', 
															'select'=>'Select', 
															'multi_select' => 'Multiselect Box', 
															'checkbox'=>'Checkbox', 
															'radio'=>'Radio', 
															'file' => 'File Upload',
															'plain_text' => 'Plain Text',
															'conditional_text' => 'Verification Code',
									);
									foreach ($field_types as $k=>$v){
										$selected = ($meta_arr['type']==$k) ? 'selected' : '';
										?>
										<option value="<?php echo $k?>" <?php echo $selected;?>><?php echo $v?></option>
										<?php 	
									}
								?>
							</select>
						</div>
						<?php 
							$display = 'none';
							if ($meta_arr['type']=='select' || $meta_arr['type']=='checkbox' || $meta_arr['type']=='radio' || $meta_arr['type']=='multi_select'){
								if ($meta_arr['name']!='tos')
								$display = 'block';
							}
						?>
						<div class="iump-form-line" id="ihc-register-field-values" style="display: <?php echo $display;?>">
							<label class="iump-labels" style="vertical-align: top;"><?php _e('Values:', 'ihc');?></label>
							<div style="display: inline-block;" class="ihc-register-the-values">
								<?php 
									if (isset($meta_arr['values']) && $meta_arr['values']){
										foreach ($meta_arr['values'] as $value){
											?>
											<div style="display: block;">
												<input type="text" name="values[]" value="<?php echo ihc_correct_text($value);?>"/>
												<i class="fa-ihc ihc-icon-remove-e" style="cursor: pointer;" onclick="jQuery(this).parent().remove();"></i>
											</div>
											<?php 
										}
									} else {
										?>
										<div style="display: block;">
											<input type="text" name="values[]" value=""/>
											<i class="fa-ihc ihc-icon-remove-e" style="cursor: pointer;" onclick="jQuery(this).parent().remove();"></i>
										</div>
										<?php 
									}
								?>														 
							</div>
							<div class="ihc-clear"></div>
							<div style="display: inline-block; margin-left: 140px; margin-top: 10px; padding: 5px; background-color: #27bebe; color: #fff; cursor: pointer;" onclick="ihc_add_new_register_field_value();">
							<?php _e('Add New Value', 'ihc');?>
							</div>
						</div>
						
						<div id="ihc-register-field-conditional-text" style="display: <?php if ($meta_arr['type']=='conditional_text') echo 'block'; else echo 'none';?>">
							<div class="iump-form-line">
								<label class="iump-labels" style="vertical-align: top;"><?php _e('Right Answer:', 'ihc');?> </label>
								<input type="text" value="<?php echo ihc_correct_text(@$meta_arr['conditional_text']);?>" name="conditional_text" /> 
							</div>
							<div class="iump-form-line">
								<label class="iump-labels" style="vertical-align: top;"><?php _e('Error Message:', 'ihc');?> </label>
								<textarea name="error_message" style="min-width: 250px;"><?php echo ihc_correct_text(@$meta_arr['error_message']);?></textarea> 														
							</div>
						</div>
						
						<div class="iump-no-border" id="ihc-register-field-plain-text" style="display: <?php if ($meta_arr['type']=='plain_text') echo 'block'; else echo 'none';?>">
							<label class="iump-labels" style="vertical-align: top;"><?php _e('Content:', 'ihc');?> </label>
							<div style="display: inline-block; max-width: 85%;">
							<?php 
							$settings = array(
									'media_buttons' => true,
									'textarea_name'=>'plain_text_value',
									'textarea_rows' => 5,
									'tinymce' => true,
									'quicktags' => true,
									'teeny' => true,
							);
							wp_editor(ihc_correct_text(@$meta_arr['plain_text_value']), 'plain_text_value', $settings);
							?>
							</div>
						</div>
						
						<div class="iump-special-line">
							<?php 
								$posible_values[-1] = __('No level selected', 'ihc');
								$levels = get_option('ihc_levels');
								if ($levels){
									foreach ($levels as $id=>$level){
										$posible_values[$id] = $level['name'];
									}
								}								
								if (!isset($meta_arr['target_levels'])){
									$meta_arr['target_levels'] = '';
								}
							?>
							<h2><?php _e('Targeting Levels', 'ihc');?></h2>
							<label class="iump-labels"><?php _e('to show up for:', 'ihc');?></label>
							<select name="" id="" class="iump-form-select " onchange="ihc_writeTagValue_cfl(this, '#indeed-target-levels-cf', '#ihc_select_levels_cf_view', 'ihc-level-select-v-');" style="min-width: 250px;">
								<option value="-2" selected="">...</option>
								<?php 
								foreach ($posible_values as $k=>$v){
									?>
									<option value="<?php echo $k;?>"><?php echo $v;?></option>
									<?php 
								}
								?>
							</select>
							<input type="hidden" name="target_levels" id="indeed-target-levels-cf" value="<?php echo $meta_arr['target_levels'];?>" />
							<div id="ihc_select_levels_cf_view">
								<?php 
									if ($meta_arr['target_levels']!=''){
										$target_levels = explode(',', $meta_arr['target_levels']);
										$str = '';
										foreach ($target_levels as $v){
											$v = (int)$v;
											if ($v>-1){
												$temp_data = ihc_get_level_by_id($v);
											} else {
												$temp_data['name'] = __('No level selected', 'ihc');
											}
											if ($temp_data){
												$str .= '<div id="ihc-level-select-v-'.$v.'" class="ihc-tag-item">'.$temp_data['name']
												. '<div class="ihc-remove-tag" onclick="ihcremoveTag('.$v.', \'#ihc-level-select-v-\', \'#indeed-target-levels-cf\');" title="'.__('Removing tag', 'ihc').'">'
												. 'x</div>'
												. '</div>';
											}
										}
										echo $str;
									}							
								?>
							</div>
						</div>	
												
						<div class="iump-form-line iump-no-border">
						<h2><?php _e("Labels", 'ihc');?></h2>
							<label class="iump-labels"><?php _e('Field Label:', 'ihc');?> </label> <input type="text" name="label" value="<?php echo ihc_correct_text($meta_arr['label']);?>"/>
						</div>							
						<div class="iump-form-line">
							<label class="iump-labels"><?php _e('SubLabel:', 'ihc');?></label>
							<input type="text" value="<?php echo ihc_correct_text(@$meta_arr['sublabel']);?>" name="sublabel" style="width: 400px;" />
						</div>		
						<?php if (empty($meta_arr['class'])) $meta_arr['class'] = '';?>
						<div class="iump-form-line iump-no-border">
							<label class="iump-labels"><?php _e('style Class:', 'ihc');?> </label> <input type="text" name="class" value="<?php echo ihc_correct_text($meta_arr['class']);?>"/>
						</div>
						<?php 
							if ($meta_arr['name']=='payment_select'){
								?>
								<div class="iump-form-line iump-no-border">
									<h2><?php _e("Template", 'ihc');?></h2>
									<p>Payment selection showcase</p>
									<select name="theme"><?php 
										if (empty($meta_arr['theme'])) $meta_arr['theme'] = 'ihc-select-payment-theme-1';
										foreach (array('ihc-select-payment-theme-1' => 'RadioBox', 'ihc-select-payment-theme-2' => 'Logos', 'ihc-select-payment-theme-3' => 'DropDown') as $k=>$v){
											?>
											<option value="<?php echo $k;?>" <?php if ($k==$meta_arr['theme']) echo 'selected';?> ><?php echo $v;?></option>
											<?php 
										}
									?></select>
								</div>
								<?php 	
							}
						?>	
						
						<?php 
							if (!in_array($meta_arr['name'], array('payment_select', 'ihc_social_media', 'tos', 'ihc_avatar', 'recaptcha'))){
						?>
						<div class="iump-special-line">
							<h2><?php _e("Conditional Logic", 'ihc');?></h2>
							<div class="iump-form-line">
								<label class="iump-labels"><?php _e('Show:', 'ihc');?></label>
								<select name="conditional_logic_show">
									<option <?php if (@$meta_arr['conditional_logic_show']=='yes') echo 'selected';?> value="yes"><?php _e("Yes", 'ihc');?></option>
									<option <?php if (@$meta_arr['conditional_logic_show']=='no') echo 'selected';?> value="no"><?php _e("No", 'ihc');?></option>
								</select>								
							</div>	
							<div class="">
								<div style="display: inline-block;">
									<label class="iump-labels"><?php _e('If Field:', 'ihc');?></label>
									<select name="conditional_logic_corresp_field">
									<?php 
										if (empty($meta_arr['conditional_logic_corresp_field'])){
											$meta_arr['conditional_logic_corresp_field'] = -1;
										}
										$register_fields = array('-1'=>'...') + ihc_get_public_register_fields($meta_arr['name']);
										foreach ($register_fields as $k => $v){
											$selected = ($meta_arr['conditional_logic_corresp_field']==$k) ? 'selected' : '';
											?>
											<option value="<?php echo $k?>" <?php echo $selected;?>><?php echo $v?></option>
											<?php  
										}
									?>
									</select>
								</div>
								<div style="display: inline-block;margin-left: 20px;">									
									<select name="conditional_logic_cond_type">
										<option <?php if (@$meta_arr['conditional_logic_cond_type']=='has') echo 'selected';?> value="has"><?php _e("Is", 'ihc');?></option>
										<option <?php if (@$meta_arr['conditional_logic_cond_type']=='contain') echo 'selected';?> value="contain"><?php _e("Contains", 'ihc');?></option>
									</select>
								</div>		
								<div style="display: inline-block;margin-left: 10px">
									<label style="display: inline-block;margin-right:10px;"> : </label>
									<input type="text" name="conditional_logic_corresp_field_value" value="<?php echo ihc_correct_text(@$meta_arr['conditional_logic_corresp_field_value']);?>" style="vertical-align: middle; min-width: 250px;" />
								</div>																
							</div>											
						</div>	
						<?php } ?>
						
						
						<div style="margin-top: 15px;">
							<input type="submit" value="<?php _e('Save', 'ihc');?>" name="<?php echo $bttn;?>" class="button button-primary button-large" />
						</div>	
					
				</div>
			</form>			
		<?php 		
	break;
}

<?php 
echo ihc_check_default_pages_set();//set default pages message
echo ihc_check_payment_gateways();
if (isset($_POST['ihc_save'])){
	//update/save
	ihc_save_update_metas('account_page');
}
$meta_arr = ihc_return_meta_arr('account_page');

?>
<div class="iump-page-title">Ultimate Membership Pro - 
							<span class="second-text">
								<?php _e('Accont Page', 'ihc');?>
							</span>
						</div>
			<div class="ihc-stuffbox">
				<div class="impu-shortcode-display">
					[ihc-user-page]
				</div>
			</div>		
<div class="metabox-holder indeed">			
<form action="" method="post">
	<div class="ihc-stuffbox">
		<h3><?php _e('Account Page Settings:', 'ihc');?></h3>
		<div class="inside">
		
			<div class="iump-register-select-template" style="padding:20px 0 35px 20px;">
				<?php _e('Select Template:', 'ihc');?>
				<select name="ihc_ap_theme"  style="min-width:300px; margin-left:10px;"><?php 
					$themes = array('ihc-ap-theme-1' => 'Theme 1');
					foreach ($themes as $k=>$v){
						?>
						<option value="<?php echo $k;?>"><?php echo $v;?></option>
						<?php 
					}
				?></select>
			</div>	
			
			<div class="inside">
				<h2><?php _e('Top Section:', 'ihc');?></h2>
				<span class="iump-labels-onbutton"><?php _e('Show Avatar Image:', 'ihc');?></span>
				<label class="iump_label_shiwtch iump-onbutton">
					<?php $checked = ($meta_arr['ihc_ap_edit_show_avatar']) ? 'checked' : '';?>
					<input type="checkbox" class="iump-switch" onClick="iump_check_and_h(this, '#ihc_ap_edit_show_avatar');" <?php echo $checked;?> />
					<div class="switch" style="display:inline-block;"></div>
				</label>
				<input type="hidden" value="<?php echo $meta_arr['ihc_ap_edit_show_avatar'];?>" name="ihc_ap_edit_show_avatar" id="ihc_ap_edit_show_avatar" /> 				
			</div>		
			
			<div class="inside" style="padding-bottom:30px;">
				<span class="iump-labels-onbutton" style="float:left; padding-right:5px; box-sizing:border-box; width:10%;"><?php _e('Welcome Message:', 'ihc');?></span>
				<div class="iump-wp_editor" style="float:left; width:80%;">
				<?php wp_editor(stripslashes($meta_arr['ihc_ap_welcome_msg']), 'ihc_ap_welcome_msg', array('textarea_name'=>'ihc_ap_welcome_msg', 'editor_height'=>200));?>
				</div>
				<div style="float:left; width:10%; font-weight:bold; color:#999; padding-left:10px; box-sizing:border-box; ">
					<div>{first_name}</div>
					<div>{last_name}</div>
					<div>{username}</div>
					<div>{user_email}</div>
				</div>
				<div class="ihc-clear"></div>
			</div>				
			
			<div class="iump-special-line">
			  <div class="inside">
				<h2><?php _e('Tabs To Show:', 'ihc');?></h2>
				<?php 
					$available_tabs = array('overview'=>__('Overview', 'ihc'),
											'profile'=>__('Profile', 'ihc'),											
											'subscription'=>__('Subscription', 'ihc'),
											'social'=>__('Social Plus', 'ihc'),
											'transactions'=>__('Transactions', 'ihc'),
					);
					$tabs = explode(',', $meta_arr['ihc_ap_tabs']);
					foreach ($available_tabs as $k=>$v){
						?>
						<div style="margin: 7px 12px;">
							<span class="iump-labels-onbutton" style="font-weight:400; min-width:100px;"><?php echo $v;?></span>
							<label class="iump_label_shiwtch  iump-onbutton">
								<?php $checked = (in_array($k, $tabs)) ? 'checked' : '';?>
								<input type="checkbox" class="iump-switch" onClick="ihc_make_inputh_string(this, '<?php echo $k;?>', '#ihc_ap_tabs');" <?php echo $checked;?> />
								<div class="switch" style="display:inline-block;"></div>
							</label>						
						</div>
						<?php 	
					}
				?>
				<input type="hidden" value="<?php echo $meta_arr['ihc_ap_tabs'];?>" id="ihc_ap_tabs" name="ihc_ap_tabs" />
			   </div>
			</div>
			
			<div class="" style="padding: 10px 5px;border-bottom: 1px solid #fafafa;">
				<h2><?php _e('Overview Standard Message:', 'ihc');?></h2>
				<p><?php _e('General content for Users who does not have assigned a Page to show up into "Overview" section:', 'ihc');?></p>
				<div  class="iump-wp_editor" style="float:left; width:80%;">
					<?php wp_editor(stripslashes($meta_arr['ihc_ap_overview_msg']), 'ihc_ap_overview_msg', array('textarea_name'=>'ihc_ap_overview_msg', 'editor_height'=>200));?>
				</div>
				<div style="float:left; width:10%; font-weight:bold; color:#999; padding-left:10px; box-sizing:border-box; ">

				
				</div>
				
				<div class="ihc-clear"></div>
				<h2><?php _e('Social Plus Top Message:', 'ihc');?></h2>
				<div  class="iump-wp_editor" style="float:left; width:80%;">
					<?php wp_editor(stripslashes(@$meta_arr['ihc_ap_social_plus_message']), 'ihc_ap_social_plus_message', array('textarea_name'=>'ihc_ap_social_plus_message', 'editor_height'=>200));?>
				</div>
								
				<div class="ihc-clear"></div>	
				<div class="ihc-wrapp-submit-bttn">
					<input type="submit" value="<?php _e('Save', 'ihc');?>" name="ihc_save" class="button button-primary button-large"  style="min-width:50px;" />
				</div>
			</div>		
			
			<div class="iump-form-line">
				<h2><?php _e('Custom CSS:', 'ihc');?></h2>

					<textarea id="ihc_account_page_custom_css"  name="ihc_account_page_custom_css" class="ihc-dashboard-textarea-full"  style="max-width:80%;"><?php echo $meta_arr['ihc_account_page_custom_css'];?></textarea>
					<div class="ihc-wrapp-submit-bttn">
						<input type="submit" value="<?php _e('Save', 'ihc');?>" name="ihc_save" class="button button-primary button-large"  style="min-width:50px;" />
					</div>	

			</div>		
					
					
		</div>
	</div>
</form>
</div>
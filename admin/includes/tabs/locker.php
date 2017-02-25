<?php 
ihc_delete_template();//DELETE
ihc_save_update_template();//SAVE, UPDATE
?>
<div class="ihc-subtab-menu">
	<a class="ihc-subtab-menu-item" href="<?php echo $url.'&tab='.$tab.'&subtab=add_new';?>"><?php _e('Add New Locker', 'ihc');?></a>
	<a class="ihc-subtab-menu-item" href="<?php echo $url.'&tab='.$tab.'&subtab=lockers_list';?>"><?php _e('Manage Lockers', 'ihc');?></a>	
</div>
<?php 
	echo ihc_check_default_pages_set();//set default pages message
	echo ihc_check_payment_gateways();
?>
<div class="ihc-dashboard-form-wrap">
<?php 

	$subtab = 'lockers_list';
	if (isset($_REQUEST['subtab'])){
		$subtab = $_REQUEST['subtab'];
	}
	if ($subtab=='add_new'){
		if (isset($_REQUEST['ihc_edit_id']) && $_REQUEST['ihc_edit_id']){
			//edit
			$meta_arr = ihc_return_meta('ihc_lockers', $_REQUEST['ihc_edit_id']);
		} else {
			//new
			$meta_arr = ihc_locker_meta_keys();
		}
				
		///////////////////// ADD NEW/edit SETION
		?>
			<form method="post" action="<?php echo $url.'&tab='.$tab.'&subtab=lockers_list';?>">
				<?php 
					if(isset($_REQUEST['ihc_edit_id']) && $_REQUEST['ihc_edit_id']!=''){
						echo '<input type="hidden" value="'.$_REQUEST['ihc_edit_id'].'" name="template_id" />';//for update
					}
				?>
				
				<div class="ihc-stuffbox">
					<h3><?php _e('Locker Name', 'ihc');?></h3>
					<div class="inside">
						<div class="iump-form-line iump-no-border">
						<input type="text" value="<?php echo $meta_arr['ihc_locker_name'];?>" name="ihc_locker_name" />
						</div>
						<div class="ihc-stuffbox-submit-wrap">
							<input type="submit" value="<?php _e('Save', 'ihc');?>" name="ihc_bttn" class="button button-primary button-large" />
						</div>							
					</div>
				</div>
				
				<div class="ihc-stuffbox">
					<h3><?php _e('Locker Template', 'ihc');?></h3>
					<div class="inside">	
						<div class="iump-form-line iump-no-border">
							<?php 
								$templates = array(1=>'Default', 2=>'Basic', 3=>'Zipped', 4=>'Zone', 5=>'Majic Transparent', 6=>'Star', 7=>'Clouddy', 8=>'Darks');
							?>
							<select name="ihc_locker_template" id="ihc_locker_template" onChange="setAddVal(this, '#ihc_locker_login_template');ihc_locker_preview();">
								<?php 
									foreach($templates as $k=>$v){
										?>
											<option value="<?php echo $k;?>" <?php if($k==$meta_arr['ihc_locker_template'])echo 'selected';?> >
												<?php echo $v;?>
											</option>
										<?php 	
									}
								?>
							</select>
							<input type="hidden" id="ihc_locker_login_template" name="ihc_locker_login_template" value="<?php echo $meta_arr['ihc_locker_login_template'];?>" />						
						</div>

						<div class="ihc-stuffbox-submit-wrap">
							<input type="submit" value="<?php _e('Save', 'ihc');?>" name="ihc_bttn" class="button button-primary button-large" />
						</div>						
					</div>
				</div>
						
				
				<div class="ihc-stuffbox">
					<h3><?php _e('Additional Display Options', 'ihc');?></h3>
					<div class="inside">	
						<div class="iump-form-line iump-no-border">
							<input type="checkbox" onClick="check_and_h(this, '#ihc_locker_login_form');ihc_locker_preview();" <?php if($meta_arr['ihc_locker_login_form']==1)echo 'checked';?> /> <span  style="color: #21759b; font-weight:bold;"><?php _e('Login Form', 'ihc');?></span>
							<input type="hidden" id="ihc_locker_login_form" name="ihc_locker_login_form" value="<?php echo $meta_arr['ihc_locker_login_form'];?>" />
						</div>
						<div class="iump-form-line iump-no-border">
							<input type="checkbox" onClick="check_and_h(this, '#ihc_locker_additional_links');ihc_locker_preview();" <?php if($meta_arr['ihc_locker_additional_links']==1)echo 'checked';?> /><span  style="color: #21759b; font-weight:bold;"><?php _e('Additional Links', 'ihc');?></span>
							<input type="hidden" id="ihc_locker_additional_links" name="ihc_locker_additional_links" value="<?php echo $meta_arr['ihc_locker_additional_links'];?>" />
						</div>						
						<div class="iump-form-line iump-no-border">
							<input type="checkbox" onClick="check_and_h(this, '#ihc_locker_display_sm');ihc_locker_preview();" <?php if ($meta_arr['ihc_locker_display_sm']==1) echo 'checked';?> /><span  style="color: #21759b; font-weight:bold;"><?php _e('Display Social Media Login', 'ihc');?></span>
							<input type="hidden" id="ihc_locker_display_sm" name="ihc_locker_display_sm" value="<?php echo @$meta_arr['ihc_locker_display_sm'];?>" />
						</div>	
												
						<div class="ihc-stuffbox-submit-wrap">
							<input type="submit" value="<?php _e('Save', 'ihc');?>" name="ihc_bttn" class="button button-primary button-large" />
						</div>						
					</div>
				</div>				
				
				<div class="ihc-stuffbox">
					<h3><?php _e('Locker Messsage', 'ihc');?></h3>
					<div class="inside">	
						<div style="width: 90%;margin: 10px;">	
							<?php 
								$settings = array(
										'media_buttons' => true,
										'textarea_name' => 'ihc_locker_custom_content',
										'textarea_rows' => 5,
										'tinymce' => true,
										'quicktags' => true,
										'teeny' => true,
								);
								$meta_arr['ihc_locker_custom_content'] = ihc_correct_text($meta_arr['ihc_locker_custom_content']);
								wp_editor( $meta_arr['ihc_locker_custom_content'], 'ihc_locker_custom_content', $settings );
							?>	
						</div>
						<input type="button" onClick="ihc_updateTextarea()" id="ihc-update-bttn-show-edit" value="<?php _e('Update', 'ihc');?>" style="display: none;" class="ihc-custom-mini-bttn"/>
						<div class="ihc-stuffbox-submit-wrap">
							<input type="submit" value="<?php _e('Save', 'ihc');?>" name="ihc_bttn" class="button button-primary button-large" />
						</div>	
					</div>
				</div>
				
				<div class="ihc-stuffbox">
					<h3><?php _e('Locker Preview', 'ihc');?></h3>
					<div class="inside">	
						<div id="locker-preview"></div>
					</div>			
				</div>		
				
				<div class="ihc-stuffbox">
					<h3><?php _e('Custom CSS', 'ihc');?></h3>
					<div class="inside">
						<textarea id="ihc_locker_custom_css" name="ihc_locker_custom_css" onBlur="ihc_locker_preview();" class="ihc-dashboard-textarea-full"><?php echo $meta_arr['ihc_locker_custom_css'];?></textarea>
						<div class="ihc-wrapp-submit-bttn">
							<input type="submit" value="<?php _e('Save', 'ihc');?>" name="ihc_bttn" class="button button-primary button-large" />
						</div>	
					</div>
				</div>				
				
			</form>		
			<script>
				jQuery(document).ready(function(){
					ihc_locker_preview();
				});	
				jQuery(document).on('click', '#ihc_locker_custom_content-html', function() {
				    jQuery('#ihc-update-bttn-show-edit').css('display', 'none');
				});
				jQuery(document).on('click', '#ihc_locker_custom_content-tmce', function() {
				    jQuery('#ihc-update-bttn-show-edit').css('display', 'block');
				});
				jQuery(window).bind('load', function(){
				    display = jQuery('#ihc_locker_custom_content').css('display');
				    if(display=='none') jQuery('#ihc-update-bttn-show-edit').css('display', 'block');
				});
									
			</script>
			
		<?php 
	}else{
		?>
		<div class="iump-page-title">Ultimate Membership Pro - 
							<span class="second-text">
								<?php _e('Inside Lockers', 'ihc');?>
							</span>
						</div>
		<?php
		////////////////// LIST LOCKER
		$templates = ihc_return_meta('ihc_lockers');
		if($templates){
		?>
		
			<div class="ihc-manage-templates">
				<div class="">
						<table class="wp-list-table widefat fixed tags">
						    <thead>
						        <tr>
									<th class="manage-column" style="width:30px;"><?php _e('ID', 'ihc');?></th>
									<th class="manage-column"><?php _e('Name', 'ihc');?></th>
									<th class="manage-column"><?php _e('Templates', 'ihc');?></th>
									<th class="manage-column"><?php _e('Edit', 'ihc');?></th>
									<th class="manage-column"><?php _e('Preview', 'ihc');?></th>			
									<th class="manage-column"><?php _e('Delete', 'ihc');?></th>	
								</tr>
							</thead>
						    <tfoot>
						        <tr>
									<th class="manage-column"><?php _e('ID', 'ihc');?></th>
									<th class="manage-column"><?php _e('Name', 'ihc');?></th>
									<th class="manage-column"><?php _e('Templates', 'ihc');?></th>
									<th class="manage-column"><?php _e('Edit', 'ihc');?></th>
									<th class="manage-column"><?php _e('Preview', 'ihc');?></th>			
									<th class="manage-column"><?php _e('Delete', 'ihc');?></th>						        
						        </tr>
						    </tfoot>								
						<?php 
						$i= 1;
						foreach($templates as $k=>$v){
							?>
							<tr class="<?php if($i%2==0) echo 'alternate';?>">
								<td><?php echo $k;?></td>
								<td style="color: #21759b; font-weight:bold;font-family: 'Oswald', arial, sans-serif !important;font-size: 14px;font-weight: 400;">
									<?php 
										echo $v['ihc_locker_name'];
									?>
								</td>
								<td>
								<span class="subcr-type-list">
									<?php 
									$templates = array(1=>'Default', 2=>'Basic', 3=>'Zipped', 4=>'Zone', 5=>'Majic Transparent', 6=>'Star', 7=>'Clouddy', 8=>'Darks');
										echo $templates [$v['ihc_locker_template']];
									?>
									</span>
								</td>
								<td>
									<a href="<?php echo $url.'&tab=locker&subtab=add_new&ihc_edit_id='.$k;?>">
										<i class="fa-ihc ihc-icon-edit-e"></i>
									</a>
								</td>
								<td>
									<a href="javascript:void(0)" onClick='ihc_locker_preview_wi(<?php echo $k;?>, 1);'>
										<i class="fa-ihc ihc-icon-preview"></i>
									</a>
								</td>			
								<td>
									<a href="<?php echo $url.'&tab=locker&subtab=lockers_list&i_delete_id='.$k;?>">
										<i class="fa-ihc ihc-icon-remove-e"></i>
									</a>
								</td>
							</tr>		
							<?php 
							$i++;
						}
						?>
						</table>
				</div>
			</div>
			<div id="locker-preview"></div>
		<?php 
		}else{
			?>
				<div class="ihc-warning-message"> <?php _e('No Lockers available! Please create your first Loker.', 'ihc');?></div> 
			<?php 	
		}
	}
?>

</div>
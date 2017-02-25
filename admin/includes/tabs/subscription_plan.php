<?php
echo ihc_check_default_pages_set();//set default pages message
echo ihc_check_payment_gateways();
?>
<div>
	<div class="iump-page-title">
		Ultimate Membership Pro - 
		<span class="second-text">
			<?php _e('Subscription Plan', 'ihc');?>
		</span>
	</div>
	<div class="ihc-stuffbox">
				<div class="impu-shortcode-display">
					[ihc-select-level]
				</div>
			</div>	
<div class="metabox-holder indeed">
<?php
		ihc_save_update_metas('general-subscription');//save update metas
		$meta_arr = ihc_return_meta_arr('general-subscription');//getting metas
		
		?>
					<form action="" method="post">
						<div class="ihc-stuffbox">
							<h3> <?php _e("Registration 'Select Level' Showcase:", 'ihc');?></h3>
							<div class="inside">
							 <div class="iump-register-select-template">	
								<?php _e('Select Template:', 'ihc');?> <select name="ihc_level_template" id="ihc_level_template" onChange="ihc_preview_select_levels();" style="min-width:300px;">
									<?php 
										$templates = array( 'ihc_level_template_1'=>__('Template', 'ihc') . ' 1', 
														    'ihc_level_template_2'=>__('Template', 'ihc') . ' 2',
															'ihc_level_template_3'=>__('Template', 'ihc') . ' 3',
															'ihc_level_template_4'=>__('Template', 'ihc') . ' 4' );
										foreach($templates as $k=>$v){
											?>
												<option value="<?php echo $k;?>" <?php if ($k==$meta_arr['ihc_level_template']) echo 'selected';?> ><?php echo $v;?></option>
											<?php 
										}
									?>
								</select>
							  </div>
								<div style="margin: 10px 0px;">									
									<div id="ihc_preview_levels"></div>
								</div>
								
								<div class="ihc-wrapp-submit-bttn">
									<input type="submit" value="<?php _e('Save', 'ihc');?>" name="ihc_save" class="button button-primary button-large" />
								</div>					
							</div>
						</div>	
						<div class="ihc-stuffbox">
							<h3><?php _e('Custom CSS', 'ihc');?></h3>
							<div class="inside">
								<textarea id="ihc_select_level_custom_css" onBlur="ihc_preview_select_levels();" name="ihc_select_level_custom_css" class="ihc-dashboard-textarea-full"><?php echo @$meta_arr['ihc_select_level_custom_css'];?></textarea>
								<div class="ihc-wrapp-submit-bttn">
									<input type="submit" value="<?php _e('Save', 'ihc');?>" name="ihc_save" class="button button-primary button-large">
								</div>	
							</div>
						</div>					
					</form>
					<script>
						 jQuery(document).ready(function(){
							 ihc_preview_select_levels();
						 });
					</script>

		
</div>
</div>
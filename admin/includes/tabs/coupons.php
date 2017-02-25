<div class="ihc-subtab-menu">
	<a class="ihc-subtab-menu-item" href="<?php echo $url.'&tab='.$tab.'&subtab=add_edit';?>"><?php _e('Add New Coupon', 'ihc');?></a>
	<a class="ihc-subtab-menu-item" href="<?php echo $url.'&tab='.$tab.'&subtab=manage';?>"><?php _e('Manage Coupons', 'ihc');?></a>	
</div>
<?php 
	echo ihc_check_default_pages_set();//set default pages message
	echo ihc_check_payment_gateways();
	
	if (empty($_GET['subtab'])){
		$_GET['subtab'] = 'manage';
	}
	
	if ($_GET['subtab']=='manage'){
		/// save
		if (isset($_POST['ihc_bttn'])){
			if (empty($_POST['id'])){
				//create
				ihc_create_coupon($_POST);
			} else {
				//update
				ihc_update_coupon($_POST);
			}
		}
		///print the coupons
		$coupons = ihc_get_all_coupons();
		if ($coupons){
			$base_edit_url = $url.'&tab='.$tab.'&subtab=add_edit';
			foreach ($coupons as $id => $coupon){
				ihc_generate_coupon_box($id, $coupon, $base_edit_url);
			}			
		} else {
			?>
			<a href="<?php echo $url.'&tab='.$tab.'&subtab=add_edit';?>" class="indeed-add-new-like-wp"><?php _e("Add New", 'ihc');?></a>
			<div class="iump-page-title">Ultimate Membership Pro - <span class="second-text"><?php _e("MemberShip Coupons", 'ihc');?></span>
			</div>
			<div class="ihc-warning-message"><?php _e(" No Coupons available! Please create your first Coupon.", "ihc");?></div>
			<?php 
		}
	} else {
		$meta_arr = ihc_get_coupon_by_id(@$_GET['id']);
		?>
		<script>
			//date picker
			jQuery(document).ready(function() {
			    jQuery('#ihc_start_time').datepicker({
			        dateFormat : 'dd-mm-yy'
			    });
			    jQuery('#ihc_end_time').datepicker({
			        dateFormat : 'dd-mm-yy'
			    });
			});
		</script>
			<div class="iump-page-title"><?php  _e("Coupons", 'ihc');?></div>
			<form method="post" action="<?php echo $url.'&tab='.$tab.'&subtab=manage';?>">
				<div class="ihc-stuffbox">
					<?php if (!empty($_GET['id'])){?>
					<h3><?php _e("Edit", 'ihc');?></h3>
					<input type="hidden" name="id" value="<?php echo $_GET['id'];?>" />
					<?php } else { ?>
					<h3><?php _e("Add New", 'ihc');?></h3>
					<?php } ?>
					<div class="inside">
						<div class="iump-form-line">
							<label class="iump-labels-special"><?php _e("Code", 'ihc');?></label>
							<input type="text" value="<?php echo $meta_arr['code'];?>" name="code" id="ihc_the_coupon_code" /> <span style="font-size: 11px;color: #fff; padding: 6px 9px;-webkit-border-radius: 3px;box-radius: 3px;    background-color: rgba(240, 80, 80, 0.8);cursor: pointer;" onClick="ihc_generate_code();"><?php _e("Generate Code", "ihc");?></span>
						</div>
						<div class="iump-form-line">
							<label class="iump-labels-special"><?php _e("Type of discount", 'ihc');?></label>
							<select name="discount_type" onChange="ihc_discount_type(this.value);"><?php 
								$arr = array('price' => __("Price", 'ihc'), 'percentage'=>"Percentage (%)");
								foreach ($arr as $k=>$v){
									$selected = ($meta_arr['discount_type']==$k) ? 'selected' : '';
									?>
										<option value="<?php echo $k;?>" <?php echo $selected;?> ><?php echo $v;?></option>
									<?php 	
								}
							?></select>
						</div>
						<div class="iump-form-line">
							<label class="iump-labels-special"><?php _e("Discount Value", 'ihc');?></label>
							<input type="number" step="0.01" value="<?php echo $meta_arr['discount_value'];?>" name="discount_value"/> 
							
							<span id="discount_currency" style="display: <?php if ($meta_arr['discount_type']=='price') echo 'inline'; else echo 'none';?>"><?php echo get_option('ihc_currency');?></span>
							<span id="discount_percentage" style="display: <?php if ($meta_arr['discount_type']=='percentage') echo 'inline'; else echo 'none';?>">%</span>
						</div>
						<div class="iump-form-line">
							<label class="iump-labels-special"><?php _e("Period Type", 'ihc');?></label>
							<select name="period_type" onChange="ihc_select_sh_div(this, '#the_date_range', 'date_range');"><?php 
								$arr = array('date_range' => __("Date Range", 'ihc'), 'unlimited'=>"Unlimited");
								foreach ($arr as $k=>$v){
									$selected = ($meta_arr['period_type']==$k) ? 'selected' : '';
									?>
										<option value="<?php echo $k;?>" <?php echo $selected;?> ><?php echo $v;?></option>
									<?php 	
								}							
							?></select>
						</div>	
						<div class="iump-form-line" id="the_date_range" style="display: <?php if ($meta_arr['period_type']=='date_range') echo 'block'; else echo 'none'?>;">
							<label class="iump-labels-special"><?php _e("Date Range", 'ihc');?></label>
							<input type="text" name="start_time" id="ihc_start_time" value="<?php echo $meta_arr['start_time'];?>" /> - <input type="text" name="end_time" id="ihc_end_time" value="<?php echo $meta_arr['end_time'];?>" />
						</div>
						<div class="iump-form-line">
							<label class="iump-labels-special"><?php _e("Repeat", 'ihc');?></label>
							<input type="number" value="<?php echo $meta_arr['repeat'];?>" name="repeat" min="1" />
						</div>	
						<div class="iump-form-line">
							<label class="iump-labels-special"><?php _e("Target Level", 'ihc');?></label>
							<select name="target_level"><?php 
								$levels = get_option('ihc_levels');
								if ($levels && count($levels)){
									$levels_arr[-1] = __("All", 'ihc');
									foreach ($levels as $k=>$v){
										$levels_arr[$k] = $v['name'];
									}
								}
								foreach ($levels_arr as $k=>$v){
									$selected = ($meta_arr['target_level']==$k) ? 'selected' : '';
									?>
										<option value="<?php echo $k;?>" <?php echo $selected;?> ><?php echo $v;?></option>
									<?php 	
								}							
							?></select>							
						</div>	
						<div class="iump-form-line">
							<label class="iump-labels-special"><?php _e("On Levels with Billing Recurrence apply the Discount:", 'ihc');?></label>
							<select name="reccuring"><?php 
								$arr = array(0 => __("Just Once", 'ihc'), 1 => __("Forever", 'ihc'));
								foreach ($arr as $k=>$v){
									$selected = ($meta_arr['reccuring']==$k) ? 'selected' : '';
									?>
										<option value="<?php echo $k;?>" <?php echo $selected;?> ><?php echo $v;?></option>
									<?php 	
								}							
							?></select>							
						</div>	
						<input type="hidden" name="box_color" value="<?php echo $meta_arr['box_color'];?>" />
						<div class="ihc-wrapp-submit-bttn">
							<input type="submit" value="<?php _e('Save', 'ihc');?>" name="ihc_bttn" class="button button-primary button-large" />
						</div>																												
					</div>
				</div>
			</form>
		<?php 
	}
?>
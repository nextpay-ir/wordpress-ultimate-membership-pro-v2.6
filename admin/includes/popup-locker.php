<div class="ihc-popup-wrapp" id="popup_box">
	<div class="ihc-the-popup ihc-the-popup-locker">
        <div class="ihc-popup-top">
        	<div class="title">Membership Pro Ultimate Wp - <?php _e('Locker', 'ihc');?></div>
            <div class="close-bttn" onClick="ihc_closePopup();"></div>
            <div class="clear"></div>
        </div>
        <div class="ihc-popup-content" style="padding:0px;">
        	<div class="ihc-popup-left-section">
	        	<div>
	  				<select class="ihc-fullwidth ihc-select"  id="ihc_mb_type-shortcode">
	  					<option value="show"><?php _e('Show Content Only For', 'ihc');?></option>
	  					<option value="block" selected><?php _e('Hide Content Only For', 'ihc');?></option>
	  				</select>
	        	</div>
	        	<div>
		         	<div class="ihc-popup-label">
		         		<?php _e('Target Users:', 'ihc');?>
		         	</div>          
		         	<?php 
						if(isset($meta_arr['ihc_mb_who']) && strpos($meta_arr['ihc_mb_who'], ',')!==FALSE){
							$arr = explode(',', $meta_arr['ihc_mb_who']); 
						}else{
							$arr[] = '';
						}
						$posible_values = array('all'=>'All', 'reg'=>'Registered Users', 'unreg'=>'Unregistered Users');
						$levels = get_option('ihc_levels');
						if($levels){
							foreach($levels as $id=>$level){
								$posible_values[$id] = $level['name'];
							}
						}
					?>
					<select class="ihc-fullwidth ihc-select" id="ihc-popup-select-target" onChange="ihc_writeTagValue(this, '#ihc_mb_who-shortcode', '#ihc-popup-target-user-select-view', 'ihc_select_popuptag_' );">
						<option value="-1" selected>...</option>
						<?php
							foreach($posible_values as $k=>$v){
							?>
								<option value="<?php echo $k;?>"><?php echo $v;?></option>	
							<?php 
							}
							?>
					</select>						
					<div id="ihc-popup-target-user-select-view"></div>
					<input type="hidden" id="ihc_mb_who-shortcode" />
	        	</div>
	        	<div class="clear"></div>
	        	<div class="ihc-popup-label">
	        		<div><?php _e('Choose Locker:', 'ihc');?></div>
	        		<?php 
	        			$lockers = ihc_return_meta('ihc_lockers');
	        			if ($lockers){				
							?>
	        		<select class="ihc-fullwidth ihc-select" id="ihc_mb_template-shortcode" onChange="ihc_locker_preview_wi(this.value, 0);">
	        			<option value="-1">...</option>
	        			<?php 
	        				foreach ($lockers as $k=>$v){
	        						?>
	        							<option value="<?php echo $k;?>"><?php echo $v['ihc_locker_name'];?></option>
	        						<?php 
	        				}
	        			?>
	        		</select>							
							<?php 
	        			}else{
	        				_e('No Lockers Available.', 'ihc');
	        			}

	        		?>
	        	</div>
	        	<div class="ihc-bttn-wrap">
	        		<input type="button" class="button button-primary button-large" value="Save" onClick="tinymce.execCommand('ihc_insert_locker_shortcode');"/>
	        	</div>        	
        	</div>
        	<div class="ihc-popup-right-section">
    			<div style="  font-size: 17px; font-weight: bold; margin: -10px 0 20px 15px;"><?php _e('Preview', 'ihc');?></div>
				<div id="locker-preview">					
				</div>
    		</div>
    	</div>
    </div>
</div>
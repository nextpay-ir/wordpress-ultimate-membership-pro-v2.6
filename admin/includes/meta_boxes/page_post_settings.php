<?php
//return html meta boxes for admin section
global $post;
$meta_arr = ihc_post_metas($post->ID);

?>
<div class="ihc-class ihc-padding">
	<select class="ihc-fullwidth ihc-select" name="ihc_mb_type" id="ihc_mb_type" onChange="ihc_show_hide_drip();">
		<option value="show" <?php if($meta_arr['ihc_mb_type']=='show') echo 'selected';?> ><?php _e('Show Page Only', 'ihc');?></option>
		<option value="block" <?php if($meta_arr['ihc_mb_type']=='block') echo 'selected';?> ><?php _e('Block Page Only', 'ihc');?></option>
	</select>
</div>

<div style="margin:4px 0;">
	<div  class="ihc-padding"  style="text-align:right; margin-bottom:10px;">
	<label class="ihc-bold">...<?php _e('for', 'ihc');?></label>
		<?php
			if (isset($meta_arr['ihc_mb_who']) && strpos($meta_arr['ihc_mb_who'], ',')!==FALSE){
				$arr = explode(',', $meta_arr['ihc_mb_who']); 
			} else {
				$arr[] = $meta_arr['ihc_mb_who'];
			}
			$posible_values = array('all'=>__('All', 'ihc'), 'reg'=>__('Registered Users','ihc'), 'unreg'=>__('Unregistered Users','ihc') );
			$levels = get_option('ihc_levels');
			if ($levels){
				foreach ($levels as $id=>$level){
					$posible_values[$id] = $level['name'];
				}
			}
			?>
			<select id="ihc-change-target-user-set" onChange="ihc_writeTagValue_for_edit_post(this, '#ihc_mb_who-hidden', '#ihc_tags_field', 'ihc_select_tag_' );" style="width: auto; min-width:80%;">
				<option value="-1" selected>...</option>
				<?php 
					foreach ($posible_values as $k=>$v){
						?>
						<option value="<?php echo $k;?>"><?php echo $v;?></option>	
						<?php 
					}
				?>
			</select>
	</div>		
			<div id="ihc_tags_field">
            	<?php
            		if (isset($meta_arr['ihc_mb_who'])){
                    	if (strpos($meta_arr['ihc_mb_who'], ',')!==FALSE){
                    		$values = explode(',', $meta_arr['ihc_mb_who']);
                    	}
                        else {
                        	$values[] = $meta_arr['ihc_mb_who'];
                        }
                        if (count($values)){
                        	foreach ($values as $value){ 
                        		if (isset($posible_values[$value])){
                        			?>
		                        		<div id="ihc_select_tag_<?php echo $value;?>" class="ihc-tag-item">
		                        	    	<?php echo $posible_values[$value];?>
		                        	    	<div class="ihc-remove-tag" onclick="ihcremoveTag_for_edit_post('<?php echo $value;?>', '#ihc_select_tag_', '#ihc_mb_who-hidden');" title="<?php _e('Removing tag', 'ihc');?>">x</div>
		                        	    </div>
		                        	<?php             			
                        		}   		
                        	}//end of foreach                         	
                        }
                        ?>
                    <div class="ihc-clear"></div>
                    <?php }//end of if ?>
								
			</div>
			<div class="ihc-clear"></div>
			<input type="hidden" id="ihc_mb_who-hidden" name="ihc_mb_who" value="<?php echo $meta_arr['ihc_mb_who'];?>" />
			<div class="clear"></div>
	
</div>	 
<div class="ihc-separator"></div>	 
<div class="ihc-class ihc-padding">
	<label><?php _e('If is not allow', 'ihc');?>...</label>
	<?php 
		$select_types = array('redirect'=>__('Redirect the Page', 'ihc'), 'replace'=>__('Replace the Content', 'ihc') );
		if ($post->ID==get_option('ihc_general_redirect_default_page')){
			unset($select_types['redirect']); //unset redirect from select options 
			$meta_arr['ihc_mb_block_type'] = 'replace';//force 'ihc_mb_block_type' to be replace
			update_option('ihc_mb_block_type', 'replace');//alse change the value in db
		}			
	?>
	<select class="ihc-fullwidth ihc-select" name="ihc_mb_block_type" onChange="ihc_redirect_replace_dd(this.value);">
		<?php 
			foreach($select_types as $value=>$label){
				?>
					<option value="<?php echo $value;?>" <?php if($meta_arr['ihc_mb_block_type']==$value) echo 'selected';?> >
						<?php echo $label;?>
					</option>
				<?php 	
			}
		?>
	</select>
</div>

<div class="ihc-class ihc-padding ihc-redrep">
<?php 
	$class = 'ihc-display-none';
	if($meta_arr['ihc_mb_block_type']=='redirect') $class = 'ihc-display-block';
?>
<div class="<?php echo $class;?> " id="ihc-meta-box-redirect">
	<label class="ihc-bold" style="padding-right:5px;"><?php _e('To:', 'ihc');?></label>
	<select name="ihc_mb_redirect_to" class=" ihc-select" style="max-width:85%;">
		<option value="-1" <?php if($meta_arr['ihc_mb_redirect_to']==-1)echo 'selected';?> >...</option>
		<?php 
			$default_redirect = get_option('ihc_general_redirect_default_page');
			if($default_redirect && $default_redirect!=-1){
				?>
				<option value="<?php echo $default_redirect;?>" <?php 
							if($meta_arr['ihc_mb_redirect_to']==$default_redirect || $meta_arr['ihc_mb_redirect_to']==-1) echo 'selected';?> >
					<?php 
						echo __('default', 'ihc');
						$title = get_the_title($default_redirect);
						if ($title){
							echo " ( $title )";
						} else {
							echo " ( " . __("Custom Link:", 'ihc') . $default_redirect . " )"; 
						}
					?>
				</option>
				<?php 	
			}
			
			$pages = ihc_get_all_pages();
			$pages = $pages + ihc_get_redirect_links_as_arr_for_select();
			
			foreach ($pages as $k=>$v){
				if ($k!=$default_redirect && $k!=$post->ID){
				?>
					<option value="<?php echo $k;?>" <?php if($meta_arr['ihc_mb_redirect_to']==$k) echo 'selected';?> ><?php echo $v;?></option>
				<?php 
				}
			}
		?>
	</select>
</div>

<?php 
	$class = 'ihc-display-none';
	if($meta_arr['ihc_mb_block_type']=='replace') $class = 'ihc-display-block';
?>
<div class="<?php echo $class;?>" id="ihc-meta-box-replace" style="color: #2e7fae; font-style: italic;">
	<?php _e('Add the replacement content into the "Replace Content" Editor box.', 'ihc');?>
</div>
	 
</div>
<?php wp_enqueue_script('ihc-back_end');?>	 
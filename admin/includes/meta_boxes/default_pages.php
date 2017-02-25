<?php 
global $post;
$set_arr = ihc_get_default_pages_il(true);
if ($set_arr && count($set_arr) && in_array($post->ID, $set_arr)) {
	//if current page is set to be default page go back
	echo ihc_meta_box_page_type_message();
}else{
	$unset_arr = ihc_get_default_pages_il();//getting the unset pages
	if($unset_arr){		
		$unset_arr = ihc_get_default_pages_il();
		if($unset_arr){
			//the select
			?>
			<div class="ihc-padding">
			<div class="ihc-bold"><?php _e('Set the Page as:', 'ihc');?></div>
			<select class="ihc-fullwidth ihc-select" name="ihc_set_page_as_default_something">
				<option value="-1">...</option>
				<?php 
					foreach($unset_arr as $name=>$label){
						?>
							<option value="<?php echo $name;?>"><?php echo $label;?> <?php _e('Page', 'ihc');?></option>
						<?php 
					}
				?>
			</select>
			<input type="hidden" name="ihc_post_id" value="<?php echo $post->ID;?>" />
			</div>
			<?php 
		}
		echo '<div class="ihc-info-box">';
		echo ihc_check_default_pages_set(true);	
		echo '</div>';
	}
}
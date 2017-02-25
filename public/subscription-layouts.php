<?php
function ihc_print_subscription_layout($template, $levels, $register_url, $custom_css='', $payment_select=FALSE){
	$str = '';
	
	if (!$custom_css){
		$custom_css = get_option('ihc_select_level_custom_css');
	}
	if (!empty($custom_css)){
		$str .= '<style>' . $custom_css . '</style>';
	}
	
	$str .= '<div class="ich_level_wrap '.$template.'">';
	switch ($template){
		case 'ihc_level_template_1':
			foreach ($levels as $id => $level){
				
				$str .= '<div class="ihc-level-item">'
					. '<div class="ihc-level-item-top">'
						. '<div class="ihc-level-item-title">' . ihc_correct_text($level['label']) . '</div>'
					. '</div>'
					. '<div class="ihc-level-item-content">' . ihc_correct_text($level['description']) . '</div>'
					. '<div class="ihc-level-item-bottom">'
						. '<div class="ihc-level-item-price">' . ihc_correct_text($level['price_text']) . '</div>'
						.ihc_print_level_link( array('id'=>$id, 'register_page' => $register_url ), '', $payment_select )
						. '<div class="iump-clear"></div>'
					. '</div>'
				. '</div>';
			}		
		break;
		case 'ihc_level_template_2':
			foreach ($levels as $id => $level){
				$str .= '<div class="ihc-level-item">'
					. '<div class="ihc-level-item-top">'
						. '<div class="ihc-level-item-title">' . ihc_correct_text($level['label']) . '</div>'
					. '</div>'
					. '<div class="ihc-level-item-price">' . ihc_correct_text($level['price_text']) . '</div>'
					. '<div class="ihc-level-item-content">' . ihc_correct_text($level['description']) . '</div>'
					. '<div class="ihc-level-item-bottom">'
						.ihc_print_level_link( array('id'=>$id, 'register_page' => $register_url ), '', $payment_select )
						. '<div class="iump-clear"></div>'
					. '</div>'
				. '</div>';
			}			
		break;
		case 'ihc_level_template_3':
			foreach ($levels as $id => $level){
				$str .= '<div class="ihc-level-item">'
					. '<div class="ihc-level-item-wrap">'
					. '<div class="ihc-level-item-top">'
						. '<div class="ihc-level-item-title">' . ihc_correct_text($level['label']) . '</div>'
					. '</div>'
					. '<div class="ihc-level-item-price">' . ihc_correct_text($level['price_text']) . '</div>'
					. '<div class="ihc-level-item-content">' . ihc_correct_text($level['description']) . '</div>'
					. '<div class="ihc-level-item-bottom">'
						.ihc_print_level_link( array('id'=>$id, 'register_page' => $register_url ), '', $payment_select )
						. '<div class="iump-clear"></div>'
					. '</div>'
					. '</div>'
				. '</div>';
			}			
		break;
		case 'ihc_level_template_4':
			foreach ($levels as $id => $level){
				$str .= '<div class="ihc-level-item">'
					. '<div class="ihc-level-item-wrap">'
					. '<div class="ihc-level-item-top">'
						. '<div class="ihc-level-item-title">' . ihc_correct_text($level['label']) . '</div>'
					. '</div>'
					. '<div class="ihc-level-item-content">' . ihc_correct_text($level['description']) . '</div>'
					. '<div class="ihc-level-item-bottom">'
						. '<div class="ihc-level-item-price">' . ihc_correct_text($level['price_text']) . '</div>'
						. '<div class="ihc-level-item-link-wrap">'
							.ihc_print_level_link( array('id'=>$id, 'register_page' => $register_url ), '', $payment_select )
						. '</div>'
						. '<div class="iump-clear"></div>'
					. '</div>'
					. '</div>'
				. '</div>';
			}		
		break;
	}
	$str .= '</div>';	
	return $str;
}
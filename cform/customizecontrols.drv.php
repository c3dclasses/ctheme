<?php
//---------------------------------------------------------
// file: ccontrol.php
// desc: defines a control used in the theme
//---------------------------------------------------------

//---------------------------------------------------------
// class: Documentation_Customize_Textarea_Control
// desc: defines a control used in the theme
//---------------------------------------------------------
class Documentation_Customize_Textarea_Control extends WP_Customize_Control {	
	public $type = 'textarea';
	public function render_content(){
		?>
		<label>
			<span class="customize-control-title">
				<?php echo esc_html( $this->label ); ?>
			</span>
			<textarea class="large-text" cols="20" rows="5" <?php $this->link(); ?>>
				<?php echo esc_textarea( $this->value() ); ?>
			</textarea>
		</label>
		<?php 
	} // end render_content()
} // end Documentation_Customize_Textarea_Control()
?>


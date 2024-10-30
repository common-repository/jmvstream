<?php

namespace Jmvstream\Includes\Elementor\Widgets;

class JmvstreamVideoWidget extends \Elementor\Widget_Base {

	public function get_name() {
		return 'jmvstream_video_widget';
	}

	public function get_title() {
		return esc_html__( 'Jmvstream Video', 'jmvstream' );
	}

	public function get_icon() {
		return 'eicon-code';
	}

	public function get_categories() {
		return [ 'basic' ];
	}

	public function get_keywords() {
		return [ 'jmvstream', 'video' ];
	}

    /**
	 * Whether the reload preview is required or not.
	 *
	 * Used to determine whether the reload preview is required.
	 *
	 * @since 1.0.0
	 *
	 * @return bool Whether the reload preview is required.
	 */
	public function is_reload_preview_required() {
		return true;
	}

	protected function register_controls() {

		// Content Tab Start

		$this->start_controls_section(
			'section_shortcode',
			[
				'label' => esc_html__( 'Video Shortcode', 'jmvstream' ),
                
			]
		);

		$this->add_control(
			'shortcode',
			[
				'label' => __( 'Put the shortcode of your video', 'jmvstream' ),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'default' => __( '', 'jmvstream' ),
			]
		);

        $this->add_control(
            'jmvstream_add_video_shortcode',
            [
                'type' => 'jmvstream_add_video_shortcode',
            ]
        );

		$this->end_controls_section();

	}

	protected function render() {
		$shortcode = $this->get_settings_for_display('shortcode');
        $shortcode = do_shortcode(shortcode_unautop($shortcode));
		?>
		<?php echo $shortcode; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
        <?php
	}

    public function render_plain_content()
    {
        $this->print_unescaped_setting('shortcode');
    }

    public function content_template() {}
}
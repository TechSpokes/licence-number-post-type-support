<?php


namespace TechSpokes\LicenceNumberPostTypeSupport;

use WP_Post;

/**
 * Class Meta.
 *
 * @package TechSpokes\LicenceNumberPostTypeSupport
 */
class Meta implements MetaInterface {

	/**
	 * @var MetaInterface $instance
	 */
	protected static $instance;

	/**
	 * @var \TechSpokes\LicenceNumberPostTypeSupport\CoreInterface $plugin
	 */
	protected $plugin;

	/**
	 * @param \TechSpokes\LicenceNumberPostTypeSupport\CoreInterface $plugin The plugin interface.
	 *
	 * @return MetaInterface The Meta interface.
	 */
	public static function getInstance( CoreInterface $plugin ): MetaInterface {
		if ( !self::$instance instanceof MetaInterface ) {
			self::setInstance( new self( $plugin ) );
		}

		return self::$instance;
	}

	/**
	 * @param MetaInterface $instance The Meta interface.
	 */
	protected static function setInstance( MetaInterface $instance ) {
		self::$instance = $instance;
	}

	/**
	 * Meta constructor.
	 *
	 * @param \TechSpokes\LicenceNumberPostTypeSupport\CoreInterface $plugin The plugin interface.
	 */
	protected function __construct( CoreInterface $plugin ) {
		$this->setPlugin( $plugin );
		foreach ( $this->getPlugin()->getPostTypes() as $post_type ) {
			add_action( "add_meta_boxes_$post_type", array( $this, 'add_meta_box' ), 10, 1 );
			add_action( "save_post_$post_type", array( $this, 'save_meta' ), 10, 1 );
		}
	}

	/**
	 * @param int $post_id The ID of the post to retrieve the licence number for.
	 *
	 * @return string The licence number for the post.
	 */
	public function get_licence_number( int $post_id ): string {
		return strval( get_post_meta( $post_id, $this->getPlugin()->getMetaKey(), true ) );
	}

	/**
	 * @param \WP_Post $post The post object currently being edited.
	 *
	 * @return void
	 */
	public function add_meta_box( WP_Post $post ): void {
		add_meta_box(
			$this->getPlugin()->getFeatureName() . '-meta',
			__( 'Licence Number', 'licence-number-post-type-support' ),
			array( $this, 'render_meta_box' ),
			$post->post_type,
			'side'
		);
	}

	/**
	 * @param \WP_Post $post The post object currently being edited.
	 *
	 * @return void
	 */
	public function render_meta_box( WP_Post $post ): void {
		$input_attrs = array(
			'name'  => $this->getPlugin()->getMetaKey(),
			'id'    => str_replace( '_', '-', $this->getPlugin()->getMetaKey() ),
			'value' => $this->get_licence_number( $post->ID ),
			'type'  => 'text',
			'class' => 'widefat',
		);
		array_walk( $input_attrs, function ( &$value, $key ) {
			$value = sprintf( '%s="%s"', $key, esc_attr( $value ) );
		} );
		/** @noinspection HtmlUnknownAttribute */
		printf( '<p><input %s /></p>', implode( ' ', $input_attrs ) );
	}

	/**
	 * @param int $post_id The ID of the post being saved.
	 *
	 * @return void
	 */
	public function save_meta( int $post_id ): void {
		// check nonce and autosave status
		if (
			( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
			|| !isset( $_REQUEST['_wpnonce'] )
			|| !wp_verify_nonce(
				$_REQUEST['_wpnonce'],
				sprintf( 'update-post_%d', $post_id )
			)
		) {
			// check failed
			return;
		}
		$meta_key = $this->getPlugin()->getMetaKey();
		$value    = isset( $_REQUEST[ $meta_key ] ) ?
			sanitize_text_field( $_REQUEST[ $meta_key ] )
			: '';
		if ( empty( $value ) ) {
			delete_post_meta( $post_id, $meta_key );
		} else {
			update_post_meta( $post_id, $meta_key, $value );
		}
	}

	/**
	 * @return \TechSpokes\LicenceNumberPostTypeSupport\CoreInterface The plugin interface.
	 */
	protected function getPlugin(): CoreInterface {
		return $this->plugin;
	}

	/**
	 * @param \TechSpokes\LicenceNumberPostTypeSupport\CoreInterface $plugin The plugin interface.
	 */
	protected function setPlugin( CoreInterface $plugin ): void {
		$this->plugin = $plugin;
	}

}

<?php


namespace TechSpokes\LicenceNumberPostTypeSupport;


/**
 * Class Core
 *
 * @package TechSpokes\LicenceNumberPostTypeSupport
 */
class Core implements CoreInterface {

	public const FEATURE_NAME     = 'licence-number';
	public const DEFAULT_META_KEY = 'licence_number';

	/**
	 * @var CoreInterface $instance The plugin interface.
	 */
	protected static $instance;

	/**
	 * @var \TechSpokes\LicenceNumberPostTypeSupport\MetaInterface $meta The meta interface.
	 */
	protected $meta;

	/**
	 * @var \TechSpokes\LicenceNumberPostTypeSupport\ReaderInterface $reader The reader interface.
	 */
	protected $reader;

	/**
	 * @var string[] $post_types Array of post types supporting the licence number feature.
	 */
	protected $post_types;

	/**
	 * @var string $meta_key The meta key used to store the licence number.
	 */
	protected $meta_key;

	/**
	 * @return CoreInterface The plugin interface.
	 */
	public static function getInstance(): CoreInterface {

		if ( !( self::$instance instanceof CoreInterface ) ) {
			self::setInstance( new self() );
		}

		return self::$instance;
	}

	/**
	 * @param CoreInterface $instance
	 */
	protected static function setInstance( CoreInterface $instance ) {
		self::$instance = $instance;
	}

	/**
	 * Core constructor.
	 */
	protected function __construct() {
		// initiate the meta object.
		add_action( 'init', array( $this, 'getMeta' ), 10, 0 );
		// maybe add display of the licence number if the theme does not support it.
		add_action( 'after_setup_theme', array( $this, 'maybeAddDisplay' ), 10, 0 );
	}

	/**
	 * @return string The feature name.
	 */
	public function getFeatureName(): string {
		return self::FEATURE_NAME;
	}

	/**
	 * @return void
	 */
	public function maybeAddDisplay(): void {
		// if current theme supports the licence number feature, let it handle the display.
		if ( !current_theme_supports( $this->getFeatureName() ) ) {
			add_filter( 'the_content', array( $this, 'displayLicenceNumber' ), 0, 1 );
		}
	}

	/**
	 * @param string $content
	 *
	 * @return string
	 */
	public function displayLicenceNumber( string $content ): string {
		global $post;
		if ( !is_singular() || !post_type_supports( $post->post_type, $this->getFeatureName() ) ) {
			return $content;
		}
		$licence_number = $this->getMeta()->get_licence_number( $post->ID );
		if ( !empty( $licence_number ) ) {
			$content .= "\n\n" . wpautop( $licence_number, false );
		}

		return $content;
	}

	/**
	 * @return \TechSpokes\LicenceNumberPostTypeSupport\MetaInterface The meta interface.
	 */
	public function getMeta(): MetaInterface {
		if ( !( $this->meta instanceof MetaInterface ) ) {
			$this->setMeta( Meta::getInstance( $this ) );
		}

		return $this->meta;
	}

	/**
	 * @param \TechSpokes\LicenceNumberPostTypeSupport\MetaInterface $meta The meta interface.
	 */
	protected function setMeta( MetaInterface $meta ): void {
		$this->meta = $meta;
	}

	/**
	 * @return \TechSpokes\LicenceNumberPostTypeSupport\ReaderInterface The reader interface.
	 */
	public function getReader(): ReaderInterface {
		if ( !( $this->reader instanceof ReaderInterface ) ) {
			$this->setReader( new Reader() );
		}

		return $this->reader;
	}

	/**
	 * @param \TechSpokes\LicenceNumberPostTypeSupport\ReaderInterface $reader
	 */
	protected function setReader( ReaderInterface $reader ): void {
		$this->reader = $reader;
	}

	/**
	 * @return string[] The post types supporting the licence number feature.
	 */
	public function getPostTypes(): array {
		if ( !is_array( $this->post_types ) ) {
			$this->setPostTypes( get_post_types_by_support( self::FEATURE_NAME ) );
		}

		return $this->post_types;
	}

	/**
	 * @param string[] $post_types
	 */
	protected function setPostTypes( array $post_types ): void {
		$this->post_types = array_filter( array_map( 'sanitize_key', $post_types ) );
	}

	/**
	 * @return string
	 */
	public function getMetaKey(): string {
		if ( empty( $this->meta_key ) ) {
			$this->setMetaKey( apply_filters( 'techspokes_licence_number_meta_key', self::DEFAULT_META_KEY ) );
		}

		return $this->meta_key;
	}

	/**
	 * @param string|mixed $meta_key
	 */
	protected function setMetaKey( $meta_key ): void {
		if ( !is_string( $meta_key ) ) {
			$meta_key = strval( $meta_key );
		}
		$meta_key = sanitize_key( $meta_key );

		$this->meta_key = empty( $meta_key ) ? self::DEFAULT_META_KEY : $meta_key;
	}

}


<?php

namespace TechSpokes\LicenceNumberPostTypeSupport;


use WP_Post;

/**
 * Interface MetaInterface
 *
 * @package TechSpokes\LicenceNumberPostTypeSupport
 */
interface MetaInterface {

	/**
	 * @param \TechSpokes\LicenceNumberPostTypeSupport\CoreInterface $plugin The plugin interface.
	 *
	 * @return MetaInterface The Meta interface.
	 */
	public static function getInstance( CoreInterface $plugin ): MetaInterface;

	/**
	 * @param int $post_id The ID of the post to retrieve the licence number for.
	 *
	 * @return string The licence number for the post.
	 */
	public function get_licence_number( int $post_id ): string;

	/**
	 * @param \WP_Post $post The post object currently being edited.
	 *
	 * @return void
	 */
	public function add_meta_box( WP_Post $post ): void;

	/**
	 * @param \WP_Post $post The post object currently being edited.
	 *
	 * @return void
	 */
	public function render_meta_box( WP_Post $post ): void;

	/**
	 * @param int $post_id The ID of the post being saved.
	 *
	 * @return void
	 */
	public function save_meta( int $post_id ): void;

	/**
	 * @param int    $post_id The ID of the post being saved.
	 * @param string $value   The licence info for the post.
	 *
	 * @return bool Whether the licence info was updated in database or is the same as the current value.
	 */
	public function update_meta( int $post_id, string $value = '' ): bool;

}

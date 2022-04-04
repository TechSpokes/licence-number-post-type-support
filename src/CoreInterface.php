<?php

namespace TechSpokes\LicenceNumberPostTypeSupport;


/**
 * Interface CoreInterface
 *
 * @package TechSpokes\LicenceNumberPostTypeSupport
 */
interface CoreInterface {

	/**
	 * @return CoreInterface The plugin interface.
	 */
	public static function getInstance(): CoreInterface;

	/**
	 * @return string The feature name.
	 */
	public function getFeatureName(): string;

	/**
	 * @return \TechSpokes\LicenceNumberPostTypeSupport\MetaInterface The meta interface.
	 */
	public function getMeta(): MetaInterface;

	/**
	 * @return \TechSpokes\LicenceNumberPostTypeSupport\ReaderInterface The reader interface.
	 */
	public function getReader(): ReaderInterface;

	/**
	 * @return string[] The post types supporting the licence number feature.
	 */
	public function getPostTypes(): array;

	/**
	 * @return string
	 */
	public function getMetaKey(): string;

}

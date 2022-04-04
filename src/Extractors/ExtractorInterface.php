<?php


namespace TechSpokes\LicenceNumberPostTypeSupport\Extractors;

/**
 * Interface ExtractorInterface
 *
 * @package TechSpokes\LicenceNumberPostTypeSupport\Extractors
 */
interface ExtractorInterface {

	/**
	 * @param string $content String of text to extract the licence number from.
	 *
	 * @return string[] Array of licence numbers found in the content.
	 */
	public function extract( string $content = '' ): array;

	/**
	 * @param string $content String of text to extract the licence number from.
	 *
	 * @return string[] An array of non-formatted licence numbers found in the content.
	 */
	public function find( string $content = '' ): array;

	/**
	 * @param string[] $items An array of licence numbers to format.
	 *
	 * @return string[] An array of formatted licence numbers.
	 */
	public function format( array $items = array() ): array;

	/**
	 * @param mixed $item The item to validate.
	 *
	 * @return bool True if the item is a non-empty string.
	 */
	public function validate( $item ): bool;

}

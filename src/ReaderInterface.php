<?php

namespace TechSpokes\LicenceNumberPostTypeSupport;


/**
 * Class Reader
 *
 * @package TechSpokes\LicenceNumberPostTypeSupport
 */
interface ReaderInterface {

	/**
	 * @param string $url The URL to read licence numbers from.
	 *
	 * @return string[]|\WP_Error The licence numbers, or a WP_Error if there was an error.
	 */
	public function read_url( string $url );

	/**
	 * @param string $content The content to extract licence numbers from.
	 *
	 * @return string[] Array of licence numbers found in the content.
	 */
	public function read_string( string $content = '' ): array;

}

<?php


namespace TechSpokes\LicenceNumberPostTypeSupport;

use TechSpokes\LicenceNumberPostTypeSupport\Extractors\ExtractorInterface;
use TechSpokes\LicenceNumberPostTypeSupport\Extractors\GeneralExciseTaxID;
use TechSpokes\LicenceNumberPostTypeSupport\Extractors\TaxMapKey;
use TechSpokes\LicenceNumberPostTypeSupport\Extractors\TransientAccommodationsTaxID;
use WP_Error;

/**
 * Class Reader
 *
 * @package TechSpokes\LicenceNumberPostTypeSupport
 */
class Reader implements ReaderInterface {

	/**
	 * @var array<string, ExtractorInterface> $extractors
	 */
	protected $extractors;

	/**
	 * @param string $url The URL to read licence numbers from.
	 *
	 * @return string[]|\WP_Error The licence numbers, or a WP_Error if there was an error.
	 */
	public function read_url( string $url ) {
		$url = esc_url_raw( $url, array( 'http', 'https' ) );
		if ( empty( $url ) ) {
			return new WP_Error(
				'licence_number_reader',
				sprintf(
					__( 'Failed to validate URL: %s', 'licence-number-post-type-support' ),
					$url
				)
			);
		}
		$response = wp_remote_get( $url, array( 'timeout' => 10 ) );
		if ( is_wp_error( $response ) ) {
			$response->add(
				'licence_number_reader',
				sprintf(
					__( 'Failed to retrieve URL: %s', 'licence-number-post-type-support' ),
					$url
				)
			);

			return $response;
		}
		// check response code
		$code = absint( wp_remote_retrieve_response_code( $response ) );
		if ( 0 === $code ) {
			return new WP_Error(
				'licence_number_reader',
				sprintf(
					__( 'Failed to retrieve response code from URL: %s', 'licence-number-post-type-support' ),
					$url
				)
			);
		}
		// check errors in the response
		if ( 400 <= $code ) {
			$message = wp_remote_retrieve_response_message( $response );
			if ( empty( $message ) ) {
				$message = sprintf(
					__( 'Failed to retrieve response message from URL: %s', 'licence-number-post-type-support' ),
					$url
				);
			}

			return new WP_Error(
				$code,
				$message,
				$response
			);
		}
		// check response body
		$body = wp_remote_retrieve_body( $response );
		if ( empty( $body ) ) {
			return new WP_Error(
				'licence_number_reader',
				sprintf(
					__( 'Failed to retrieve body from URL: %s', 'licence-number-post-type-support' ),
					$url
				)
			);
		}

		return $this->read_string( $body );
	}

	/**
	 * @param string $content The content to extract licence numbers from.
	 *
	 * @return string[] Array of licence numbers found in the content.
	 */
	public function read_string( string $content = '' ): array {
		if ( empty( $content ) ) {
			return array();
		}
		$results = array();
		foreach ( $this->getExtractors() as $extractor ) {
			$items = $extractor->extract( $content );
			if ( !empty( $items ) ) {
				$results = array_merge( $results, $items );
			}
		}

		return array_unique( array_filter( $results ) );
	}

	/**
	 * @return array<string, ExtractorInterface>
	 */
	protected function getExtractors(): array {
		if ( empty( $this->extractors ) ) {
			$this->setExtractors( $this->default_extractors() );
		}

		return $this->extractors;
	}

	/**
	 * @param array<string, ExtractorInterface> $extractors
	 */
	protected function setExtractors( array $extractors ): void {
		$this->extractors = array_filter( $extractors, array( $this, 'isExtractor' ) );
	}

	/**
	 * @param mixed $maybe_extractor Element to check if it is an ExtractorInterface.
	 *
	 * @return bool True if the object is an ExtractorInterface.
	 */
	protected function isExtractor( $maybe_extractor ): bool {
		return $maybe_extractor instanceof ExtractorInterface;
	}

	/**
	 * @return array<string, ExtractorInterface> Associative array of extractor prefixes as keys and extractors as values.
	 */
	protected function default_extractors(): array {
		return apply_filters(
			'tsln_default_extractors',
			array(
				TransientAccommodationsTaxID::PREFIX => new TransientAccommodationsTaxID(),
				GeneralExciseTaxID::PREFIX           => new GeneralExciseTaxID(),
				TaxMapKey::PREFIX                    => new TaxMapKey(),
			)
		);
	}

}

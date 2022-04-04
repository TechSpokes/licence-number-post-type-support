<?php


namespace TechSpokes\LicenceNumberPostTypeSupport\Extractors;

/**
 * Class AbstractExtractor
 *
 * @package TechSpokes\LicenceNumberPostTypeSupport\Extractors
 */
abstract class AbstractExtractor implements ExtractorInterface {

	/**
	 * @const int The minimum length of a licence number.
	 */
	public const THRESHOLD = 8;

	/**
	 * @var string $prefix
	 */
	protected $prefix;

	/**
	 * @var string $regex
	 */
	protected $regex;

	/**
	 * @var int $threshold The minimum number of characters required in a licence number.
	 */
	protected $threshold;

	/**
	 * @inheritDoc
	 */
	public function extract( string $content = '' ): array {
		return $this->format( $this->find( $content ) );
	}

	/**
	 * @param string $content String of text to extract the licence number from.
	 *
	 * @return string[] An array of non-formatted licence numbers found in the content.
	 */
	public function find( string $content = '' ): array {
		if ( empty( $content ) ) {
			return array();
		}
		if ( 1 > absint( preg_match_all( $this->getRegex(), $content, $matches, PREG_PATTERN_ORDER ) ) ) {
			return array();
		}
		if ( empty( $matches[0] ) || !is_array( $matches[0] ) ) {
			return array();
		}

		return array_unique( $matches[0] );
	}

	/**
	 * @param string[] $items An array of licence numbers to format.
	 *
	 * @return string[] An array of formatted licence numbers.
	 */
	public function format( array $items = array() ): array {
		if ( empty( $items ) ) {
			return array();
		}
		$items = array_filter( $items, function ( $item ) {
			return $this->validate( $item );
		} );
		if ( empty( $items ) ) {
			return array();
		}
		$trimmable = implode( '', $this->number_separators() );
		array_walk( $items, function ( &$item ) use ( $trimmable ) {
			$item = trim( $item, $trimmable );
		} );

		return array_unique( array_filter( $items ) );
	}

	/**
	 * @param mixed $item The item to validate.
	 *
	 * @return bool True if the item is a non-empty string.
	 */
	public function validate( $item ): bool {
		return !empty( $item ) && is_string( $item ) && ( $this->getThreshold() <= strlen( $item ) );
	}

	/**
	 * @return string The licence number prefix string.
	 */
	abstract protected function prefix(): string;

	/**
	 * @return string
	 */
	protected function getPrefix(): string {
		if ( empty( $this->prefix ) ) {
			$this->setPrefix( $this->prefix() );
		}

		return $this->prefix;
	}

	/**
	 * @param string $prefix
	 */
	protected function setPrefix( string $prefix ): void {
		$this->prefix = $prefix;
	}

	/**
	 * @return string
	 */
	protected function getRegex(): string {
		if ( empty( $this->regex ) ) {
			$this->setRegex( $this->regex() );
		}

		return $this->regex;
	}

	/**
	 * @param string $regex
	 */
	protected function setRegex( string $regex ): void {
		$this->regex = $regex;
	}

	/**
	 * @return int
	 */
	protected function getThreshold(): int {
		if ( empty( $this->threshold ) ) {
			$this->setThreshold( $this->threshold() );
		}

		return $this->threshold;
	}

	/**
	 * @param int $threshold
	 */
	protected function setThreshold( int $threshold ): void {
		$this->threshold = absint( $threshold );
	}

	/**
	 * @return int The minimum number of characters required in a licence number.
	 */
	protected function threshold(): int {
		return self::THRESHOLD;
	}

	/**
	 * @return string[] Array of characters separating the licence number prefix from the licence number.
	 */
	protected function prefix_separators(): array {
		return array( ' ', '-', '_', '.' );
	}

	/**
	 * @return string The regex part matching the licence number prefix and its separators.
	 */
	protected function prefix_regex(): string {
		return $this->getPrefix() . '[' . preg_quote( implode( '', $this->prefix_separators() ), '/' ) . ']?';
	}

	/**
	 * @return string[] Array of characters separating the licence number characters.
	 */
	protected function number_separators(): array {
		return array( '-', ' ', '.', '_' );
	}

	/**
	 * @return string The part of the regex that matches the licence number.
	 *                \d by default, matches any digit.
	 */
	protected function number_characters(): string {
		return '\d';
	}

	/**
	 * @return string The regex part matching the licence number.
	 */
	protected function number_regex(): string {
		return '[' . $this->number_characters() . preg_quote( implode( '', $this->number_separators() ), '/' ) . ']+';
	}

	/**
	 * @return string The complete regex for matching the licence.
	 */
	protected function regex(): string {
		return '/' . $this->prefix_regex() . $this->number_regex() . '/';
	}

}

<?php


namespace TechSpokes\LicenceNumberPostTypeSupport\Extractors;

/**
 * Class TransientAccommodationsTaxID
 *
 * @package TechSpokes\LicenceNumberPostTypeSupport\Extractors
 */
class TransientAccommodationsTaxID extends AbstractExtractor {

	/**
	 * @const string PREFIX The prefix for the licence number.
	 */
	public const PREFIX = 'TA';

	/**
	 * @inheritDoc
	 */
	protected function prefix(): string {
		return self::PREFIX;
	}

}

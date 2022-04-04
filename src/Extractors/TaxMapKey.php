<?php


namespace TechSpokes\LicenceNumberPostTypeSupport\Extractors;

/**
 * Class TaxMapKey
 *
 * @package TechSpokes\LicenceNumberPostTypeSupport\Extractors
 */
class TaxMapKey extends AbstractExtractor {

	/**
	 * @const string PREFIX The prefix to use for the licence number.
	 */
	public const PREFIX = 'TMK';

	/**
	 * @inheritDoc
	 */
	protected function prefix(): string {
		return self::PREFIX;
	}

}

<?php


namespace TechSpokes\LicenceNumberPostTypeSupport\Extractors;

/**
 * Class GeneralExciseTaxID
 *
 * @package TechSpokes\LicenceNumberPostTypeSupport\Extractors
 */
class GeneralExciseTaxID extends AbstractExtractor {

	/**
	 * @const string PREFIX The prefix for the licence number.
	 */
	public const PREFIX = 'GE';

	/**
	 * @inheritDoc
	 */
	protected function prefix(): string {
		return self::PREFIX;
	}

}

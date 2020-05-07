<?php namespace JATSParser\HTML;

use JATSParser\Body\FundingStatement as JATSFunding;
use JATSParser\Body\Text as JATSText;
use JATSParser\HTML\Text as HTMLText;

class FundingStatement extends \DOMElement {

	function __construct($nodeName = null) {
		$nodeName === null ? parent::__construct("p") : parent::__construct($nodeName);
	}

	public function setContent(JATSFunding $jatsFunding) {

		/* @var $jatsText JATSText */
		foreach ($jatsFunding->getContent() as $jatsText) {

			HTMLText::extractText($jatsText, $this);

		}
	}
}

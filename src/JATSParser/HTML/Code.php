<?php namespace JATSParser\HTML;

use JATSParser\Body\Code as JATSCode;
use JATSParser\Body\Text as JATSText;
use JATSParser\HTML\Text as HTMLText;

class Code extends \DOMElement {

	function __construct($nodeName = null) {
		$nodeName === null ? parent::__construct("code") : parent::__construct($nodeName);
	}

	public function setContent(JATSCode $jatsCode) {

		/* @var $jatsText JATSText */
		foreach ($jatsCode->getContent() as $jatsText) {

			HTMLText::extractText($jatsText, $this);

		}
	}
}

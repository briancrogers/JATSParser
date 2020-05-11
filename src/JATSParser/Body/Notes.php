<?php namespace JATSParser\Body;

use JATSParser\Body\Table as Table;
use JATSParser\Body\Figure as Figure;
use JATSParser\Body\Listing as Listing;
use JATSParser\Body\Par as Par;
use JATSParser\Body\Section as Section;

class Notes extends Section {

	/* @var $label string */
	private $label;

	function __construct (\DOMElement $element) {
		parent::__construct($element);
	}

	// Cannot contain sections
	public function getChildSectionsTitles(): array
	{
		return array();
	}
}

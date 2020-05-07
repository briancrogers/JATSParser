<?php namespace JATSParser\Body;

use JATSParser\Body\Table as Table;
use JATSParser\Body\Figure as Figure;
use JATSParser\Body\Listing as Listing;
use JATSParser\Body\Par as Par;
use JATSParser\Body\Section as Section;

class BoxedText extends Section {

	/* @var $label string */
	private $label;

	private $attrib = array();

	function __construct (\DOMElement $element) {
		parent::__construct($element);

		$this->label = $this->extractFromElement(".//label", $element);

		$this->attrib = $this->extractFormattedText(".//attrib", $element);
	}

	// Cannot contain sections
	public function getChildSectionsTitles(): array
	{
		return array();
	}

        /**
         * @return string
         */
        public function getLabel(): ?string
        {
                return $this->label;
	}

	/**
	 * @return array|null
	 */
	public function getAttrib(): array
	{
		return $this->attrib;
	}
}

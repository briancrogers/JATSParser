<?php namespace JATSParser\HTML;

use JATSParser\Body\DispFormula as JATSFormula;
use JATSParser\Body\Text as JATSText;
use JATSParser\HTML\Text as HTMLText;

class DispFormula extends \DOMElement {

	function __construct($nodeName = null) {
		$nodeName === null ? parent::__construct("div") : parent::__construct($nodeName);
	}

	public function setContent(JATSFormula $jatsFormula) {
		$this->setAttribute("class", "LaTeXFormula");

        	// Set figure id. Needed for links from referenceces to the figure
        	$this->setAttribute("id", $jatsFormula->getId());

		/* @var $jatsText JATSText */
		foreach ($jatsFormula->getContent() as $jatsText) {
			$this->nodeValue = "\[" . trim(htmlspecialchars($jatsText)) . "\]";
                }
	}
}

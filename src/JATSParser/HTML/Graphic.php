<?php namespace JATSParser\HTML;

use JATSParser\Body\Graphic as JATSGraphic;
use JATSParser\Body\Par as JATSPar;
use JATSParser\Body\Text as JATSText;
use JATSParser\HTML\Par as Par;
use JATSParser\HTML\Text as HTMLText;

class Graphic extends \DOMElement {
	public function __construct() {

		parent::__construct("figure");

	}

	public function setContent(JATSGraphic $jatsGraphic) {
		
		$this->setAttribute("class", "figure");
		
		$srcNode = $this->ownerDocument->createElement("img");
		$srcNode->setAttribute("class", "figure-img img-fluid img-responsive");
		$srcNode->setAttribute("src", $jatsGraphic->getLink());
		$this->appendChild($srcNode);
	}
}

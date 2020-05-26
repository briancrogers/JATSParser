<?php namespace JATSParser\HTML;

use JATSParser\Body\Footnote as JATSFootnote;
use JATSParser\Body\Text as JATSText;
use JATSParser\HTML\Text as HTMLText;

class Footnote extends \DOMElement {

	private $index;

	function __construct($index) {
		parent::__construct("li");
		$this->index = $index;
	}

	public function setContent(JATSFootnote $jatsFootnote) {

		$this->setAttribute("id", "fn-" . $this->index);

		$labelNode = $this->ownerDocument->createElement("span");
		$labelNode->setAttribute("class", "footnote-label");

		$textLabel = $jatsFootnote->getLabel() ? $jatsFootnote->getLabel() : $this->index;
		$textNode = $this->ownerDocument->createTextNode($textLabel);
		$labelNode->appendChild($textNode);
		$this->appendChild($labelNode);

		$para = $this->ownerDocument->createElement("p");
		$this->appendChild($para);

		/* @var $jatsText JATSText */
		foreach ($jatsFootnote->getContent() as $jatsText) {

			HTMLText::extractText($jatsText, $para);

		}
	}
}

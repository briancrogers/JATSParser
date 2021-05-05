<?php namespace JATSParser\Body;

use JATSParser\Body\JATSElement as JATSElement;
use JATSParser\Body\Document as Document;
use JATSParser\Body\Text as Text;

class Footnote implements JATSElement {

	/**
	 *   @var $label string
	 */
	private $label;

	/**
	 *   @var $content array
	 */

	private $content;

	function __construct(\DOMElement $footnote) {
		$xpath = Document::getXpath();
		$content = array();
		$footnoteTextNodes = $xpath->query(".//text()", $footnote);
		foreach ($footnoteTextNodes as $footnoteTextNode) {
			$jatsText = new Text($footnoteTextNode);
			$content[] = $jatsText;
		}
		$this->content = $content;

		$this->label = $xpath->evaluate(".//label", $footnote)->nodeValue;
	}

	public function getContent(): array {
		return $this->content;
	}

	public function getLabel(): ?string {
		return $this->label;
	}
}

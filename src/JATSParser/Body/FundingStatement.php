<?php namespace JATSParser\Body;

use JATSParser\Body\JATSElement as JATSElement;
use JATSParser\Body\Document as Document;
use JATSParser\Body\Text as Text;

class FundingStatement implements JATSElement {

	/**
	 *   @var $content array
	 */

	private $content;

	function __construct(\DOMElement $funding) {
		$xpath = Document::getXpath();
		$content = array();
		$fundingTextNodes = $xpath->query(".//text()", $funding);
		foreach ($fundingTextNodes as $fundingTextNode) {
			$jatsText = new Text($fundingTextNode);
			$content[] = $jatsText;
		}
		$this->content = $content;
	}

	public function getContent(): array {
		return $this->content;
	}
}

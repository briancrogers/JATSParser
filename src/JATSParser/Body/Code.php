<?php namespace JATSParser\Body;

use JATSParser\Body\JATSElement as JATSElement;
use JATSParser\Body\Document as Document;
use JATSParser\Body\Text as Text;

class Code implements JATSElement {

	/**
	 *   @var $content array
	 */

	private $content;

	function __construct(\DOMElement $code) {
		$xpath = Document::getXpath();
		$content = array();
		$codeTextNodes = $xpath->query(".//text()", $code);
		foreach ($codeTextNodes as $codeTextNode) {
			$jatsText = new Text($codeTextNode);
			$content[] = $jatsText;
		}
		$this->content = $content;
	}

	public function getContent(): array {
		return $this->content;
	}
}

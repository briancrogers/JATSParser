<?php namespace JATSParser\Body;

use JATSParser\Body\JATSElement as JATSElement;
use JATSParser\Body\Document as Document;
use JATSParser\Body\Text as Text;

class Par implements JATSElement {

	/**
	 *   @var $content array
	 */

	private $content;

	function __construct(\DOMElement $paragraph) {
		$xpath = Document::getXpath();
		$content = array();
		$parTextNodes = $xpath->query(".//fn|.//text()[not(ancestor::fn)]", $paragraph);
		foreach ($parTextNodes as $parTextNode) {
			if ($parTextNode->nodeName === "fn") {
				$jatsFootnote = new Footnote($parTextNode);
				$footnote = $paragraph->ownerDocument->createElement("sup");
				$fn_link = $paragraph->ownerDocument->createElement("fn");
				Document::incrementFootnoteIndex();
				$fn_index = $jatsFootnote->getLabel() ? $jatsFootnote->getLabel() : Document::getFootnoteIndex();
				$fn_link->appendChild($paragraph->ownerDocument->createTextNode($fn_index));
				$footnote->appendChild($fn_link);
				$content[] = new Text($xpath->query("./text()[1]", $fn_link)[0]);
				$content[] = $jatsFootnote;
			} else {
				$jatsText = new Text($parTextNode);
				$content[] = $jatsText;
			}
		}
		$this->content = $content;
	}

	public function getContent(): array {
		return $this->content;
	}
}

<?php namespace JATSParser\Body;

use JATSParser\Body\Document as Document;

class Graphic extends AbstractElement {

	private $content = array();

	/* @var $link string */
	private $link;

	public function __construct(\DOMElement $graphicElement) {
		parent::__construct($graphicElement);

		$this->link = $this->extractFromElement(".//graphic/@xlink:href", $figureElement);

	}

	public function getLink(): ?string {
		return $this->link;
	}

	public function getContent() {
		return $this->content;
	}
}

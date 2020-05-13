<?php namespace JATSParser\Body;

use JATSParser\Body\Document as Document;
use JATSParser\Body\Text as Text;

class DispFormula extends AbstractElement {

	/* @var $label string */
	private $label;
	
	/* @var $id string */
	private $id;

	private $content = array();

	public function __construct(\DOMElement $element) {
		parent::__construct($element);

		$formulaItemNodes = $this->xpath->query("tex-math", $element);
		foreach ($formulaItemNodes as $formulaItemNode) {
			$formulaItem = $this->extractFromElement(".", $formulaItemNode);
			$this->content[] = $formulaItem;
		}

		$this->label = $this->extractFromElement(".//label", $element);
		$this->id = $this->extractFromElement( "./@id", $element);
	}
	
	public function getId(): ?string {
                return $this->id;
	}

	/**
         * @return string
         */
        public function getLabel(): ?string
        {
                return $this->label;
	}

	public function getContent(): ?array {
		return $this->content;
	}
}

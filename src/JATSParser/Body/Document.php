<?php namespace JATSParser\Body;

use JATSParser\Body\Section as Section;
use JATSParser\Back\Journal as Journal;
use JATSParser\Back\Book as Book;
use JATSParser\Back\Chapter as Chapter;
use JATSParser\Back\Conference as Conference;

class Document {

	/* @var $document \DOMDocument */
	private $document;

	/* @var $xpath \DOMXPath */
	private static $xpath;

	/* @var $documentPath \String */
	private $documentPath;

	/* var $articleSections array */
	private $articleContent = array();

	/* var $references array of article's References */
	private $references = array();

	private static $footnoteIndex;

	function __construct(?string $documentPath) {
		$document = new \DOMDocument;
		$this->document = $document->load($documentPath);
		self::$xpath = new \DOMXPath($document);
		self::$footnoteIndex = 0;

		$this->extractContent();
		$this->extractReferences();
	}

	public static function getXpath() : \DOMXPath {
		return self::$xpath;
	}

	public function getDocumentPath () : string {
		return $this->documentPath;
	}

	public function getArticleSections() : array {
		return $this->articleContent;
	}

	public function getReferences() : array {
		return $this->references;
	}

	public static function incrementFootnoteIndex() {
		self::$footnoteIndex += 1;
	}

	public static function getFootnoteIndex() {
		return self::$footnoteIndex;
	}

	/* @brief Constructor for references
	 * JATS XML can give us a little, if not at all, information about reference type;
	 * Here we are trying to determine the type of citation by element-citation node attribute or names of nodes which reference contains;
	 * Supported types are: journal, book, chapter, and conference.
	 */
	private function extractReferences() {
		$references = array();
		foreach(self::$xpath->evaluate("/article/back/ref-list/ref") as $reference ) {
			/* @var $reference \DOMElement */
			$citationTypeNodes = self::$xpath->query(".//element-citation[1]/@publication-type|.//mixed-citation[1]/@publication-type|.//citation-alternatives[1]/@publication-type", $reference );
			if ($citationTypeNodes->length > 0) {
				foreach ($citationTypeNodes as $citationTypeNode) {
					/* @var $citationTypeNode \DOMAttr */
					switch ($citationTypeNode->nodeValue) {
						case "journal":
							$journal = new Journal($reference);
							$references[] = $journal;
							break;
						case "book":
							$book = new Book($reference);
							$references[] = $book;
							break;
						case "chapter":
							$chapter = new Chapter($reference);
							$references[] = $chapter;
							break;
						case "conference":
							$conference = new Conference($reference);
							$references[] = $conference;
							break;
						default:
							$defaultRef = new Journal($reference);
							$references[] = $defaultRef;
							break;
					}
				}
			} else {
				$chapterTitleNode = self::$xpath->query(".//chapter-title", $reference);
				if ($chapterTitleNode->length > 0) {
					$probablyChapter = new Chapter($reference);
					$references[] = $probablyChapter;
				} else {
					$publisherName = self::$xpath->query(".//publisher-name", $reference);
					if($publisherName->length > 0) {
						$probablyBook = new Book($reference);
						$references[] = $probablyBook;
					} else {
						$confNameNode = self::$xpath->query(".//conf-name", $reference);
						if($confNameNode->length > 0) {
							$probablyConference = new Conference($reference);
							$references[] = $probablyConference;
						} else {
							$probablyJournal = new Journal($reference);
							$references[] = $probablyJournal;
						}
					}
				}
			}
		}
		$this->references = $references;
	}

	private function extractContent(): void
	{
		$articleContent = array();
		foreach (self::$xpath->evaluate("/article/body") as $body) {
			foreach (self::$xpath->evaluate(".//sec|./p|./list|./table-wrap|./fig|./media|./disp-formula|./disp-quote|./boxed-text|./verse-group", $body) as $content) {
				switch ($content->nodeName) {
					case "sec":
						$articleSection = new Section($content);
						$articleContent[] = $articleSection;
						break;
					case "p":
						$par = new Par($content);
						$articleContent[] = $par;
						break;
					case "list":
						$list = new Listing($content);
						$articleContent[] = $list;
						break;
					case "table-wrap":
						$table = new Table($content);
						$articleContent[] = $table;
						break;
					case "fig":
						$figure = new Figure($content);
						$articleContent[] = $figure;
						break;
					case "media":
						$media = new Media($content);
						$articleContent[] = $media;
						break;
					case "disp-formula":
						$dispFormula = new DispFormula($content);
						$articleContent[] = $dispFormula;
						break;
					case "disp-quote":
						$dispQuote = new DispQuote($content);
						$articleContent[] = $dispQuote;
						break;
					case "boxed-text":
						$boxedText = new BoxedText($content);
						$articleContent[] = $boxedText;
						break;
					case "verse-group":
						$verse = new Verse($content);
						$articleContent[] = $verse;
						break;
				}
			}
		}
		foreach (self::$xpath->evaluate("/article/back") as $back) {
			foreach (self::$xpath->evaluate("./ack|./app-group/app|./glossary|./notes", $back) as $backmatter) {
				switch ($backmatter->nodeName) {
					case "app":
						$articleSection = new Section($backmatter);
						$articleContent[] = $articleSection;
						break;
					case "glossary":
						$articleSection = new Glossary($backmatter);
						$articleContent[] = $articleSection;
						break;
					case "notes":
						$articleSection = new Notes($backmatter);
						$articleContent[] = $articleSection;
						break;
					case "ack":
						$articleSection = new Ack($backmatter);
						$articleContent[] = $articleSection;
						break;
				}
			}
		}
		foreach (self::$xpath->evaluate("/article/front") as $front) {
			foreach (self::$xpath->evaluate("./article-meta/funding-group/funding-statement", $front) as $frontmatter) {
				switch ($frontmatter->nodeName) {
					case "funding-statement":
						$frontStatement = new FundingStatement($frontmatter);
						$articleContent[] = $frontStatement;
						break;
				}
			}
		}
		$this->articleContent = $articleContent;
	}

}

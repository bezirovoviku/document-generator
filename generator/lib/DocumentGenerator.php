<?php
require_once('DocxDocument.php');
require_once('ParseException.php');

/**
 * This class manages generation of individual documents
 */
class DocumentGenerator
{
	///@var DocxDocument $template template used for generation
	protected $template;
	
	///@var string $tmp path to tmp folder
	protected $tmp;
	
	public function __construct() {
		
	}
	
	/**
	 * Sets template used for generation
	 * @param string $path path to template docx file
	 */
	public function setTemplate($path) {
		$this->template = new DocxDocument($path);
	}
	
	/**
	 * Sets tmp path used for generating archive
	 * @param string $tmp path to tmp folder
	 */
	public function setTmp($tmp) {
		$this->tmp = $tmp;
	}
	
	/**
	 * Generates document using specified data
	 * @param array  $data data for template
	 * @param string $path path to new document
	 */
	public function generate($data, $path) {
		//Get template body, save unaltered copy
		$body = $template = $this->template->getBody();
		
		//Now do replacing
		$body = $this->replace($body, $data);
		
		//Save newly created document
		$this->template->setBody($body);
		$this->template->save($path);
		
		//Reset template body
		$this->template->setBody($template);
	}
	
	/**
	 * Generates archive of documents based on specified data.
	 * @param array  $data array where each element is data for one document
	 * @param string $path where to save generated archive
	 * @param string $type document type, pdf or docx (don't use pdf now)
	 */
	public function generateArchive($data, $path, $type = 'docx') {
		//Prepare result archive
		$archive = new ZipArchive();
		if (!$archive->open($path, ZipArchive::CREATE)) {
			throw new Exception("Failed to open result archive.");
		}

		//Create documents and add them to archive
		foreach($data as $index => $docdata) {
			$this->generate($docdata, "{$this->tmp}/document$index.docx");
			$archive->addFile("{$this->tmp}/document$index.docx", "document$index.docx");
		}
		
		//Write documents to archive
		$archive->close();
		
		//For some reason, files are written to archive when closing, so delete after closing archive
		foreach($data as $index => $docdata) {
			unlink("{$this->tmp}/document$index.docx");
		}
	}
	
	/**
	 * Main recursive replacing function, manages loops and simple replacing
	 * @param string  $body    document to be replaced
	 * @param array   $context replacement values
	 * @param boolean $do      OPTIONAL should function actually do the replacement (used for recursion)
	 * @return string replaced document
	 */
	protected function replace($body, $context, $do = true) {		
		//Is there any cycle in this body
		//This regex is diry as
		if (preg_match("/((<w:p[^>]*>(.*?)<\/w:p>)*)(<w:p[^>]*>.*?\{\s*foreach\s+([^\s]*)\s+as\s+([^\s]*)\s*\}.*?<\/w:p>)/si", $body, $matches, PREG_OFFSET_CAPTURE)) {
			//Foreach content
			$last = count($matches) - 3;
			$part = $matches[$last][0];
			$pos = $matches[$last][1];
			
			//Load up user input
			$replace_array = $context[$matches[$last + 1][0]];
			$replace_as = $matches[$last + 2][0];
			
			//Parse body before this cycle
			$result = $this->replace(substr($body, 0, $pos), $context, $do);
			
			//Get cycle body
			$body = substr($body, $pos + strlen($part));

			//Now, we need to find this cycle ending. Simulate one cycle for this.
			$ending = $this->replace($body, $context, false);

			//Find ending tag in temporary updated body
			//This regex is diry as 
			if (!preg_match("/((<w:p[^>]*>(.*?)<\/w:p>)*)(<w:p[^>]*>.*?\{\s*\/\s*foreach\s*\}.*?<\/w:p>)/si", $ending, $matches, PREG_OFFSET_CAPTURE)) {
				throw new ParseException("Ending foreach tag not found.");
			}
			
			//Now split body to cycle and the rest. Use replacement on the rest.
			$last = $matches[count($matches)-1];
			$body = substr($ending, 0, $last[1]);
			$ending = $this->replace(substr($ending, $last[1] + strlen($last[0])), $context, $do);

			foreach($replace_array as $key => $value) {
				//@TODO: Let user decide name of index
				$context['index'] = $key;
				$context[$replace_as] = $value;
			
				$result .= $this->replace($body, $context, $do);
			}
			
			return $result . $ending;
		} else {
			if (!$do)
				return $body;
			
			//While there is something to replace
			//Ignore /foreach tag
			//@TODO: Be more specific with regex
			while(preg_match("/\{([^\/][^}]*)\}/", $body, $matches)) {
				//Entire match
				$match = $matches[0];
				//Only tag content
				$content = $matches[1];
				
				//Split to object parts
				$parts = explode('.', $content);
				
				//Now try to find said parts
				$value = $context;
				foreach($parts as $part) {
					if (isset($value[$part])) {
						$value = $value[$part];
					} else {
						$value = '';
						break;
					}
				}
				
				//Replace the tag
				$body = str_replace($match, $value, $body);
			}
			
			return $body;
		}
	}
}
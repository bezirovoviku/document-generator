<?php

return [

	/*
	|--------------------------------------------------------------------------
	| Docs Page Reminder Language Lines
	|--------------------------------------------------------------------------
	|
	| The following language lines are the english lines for docs page.
	|
	*/


	"Templates" => "Templates",	
	"Examples" => "Examples",	
	"Example" => "Example",	
	"Download" => "Download",
	"and" => "and",
	"template" => "template",
	"Document" => "Document",
	"Download" => "Download",
	"exampleTemplate" => "example template",
	"exampleData" => "example data",
	"Contents" => "Contents",
	"Documentation" => "Documentation", 

	"WordReplacing" => "Word Replacing",
	"NestedReplacing" => "Nested Replacing",
	"MultiitemReplacing" => "Multiitem Replacing",
	"MultiitemReplacingInTables" => "Multiitem Replacing In Tables",
	"TemplateFilters" => "Template Filters",

	"DateFilter" => "Date filter",
	"StringFilters" => "String filters",
	"NumberFilter" => "Number filter",
	
	"Header1" => "Documentations",	
	"Description1" => "On this page you find documentation to our document generator.",
	"Description2" => "For API documentation, please refer to our ",
	"Description3" => "Our system works with DOCX templates, format used by Microsoft Office (2007 and newer). Data should be provided",
	"Description4" => "as JSON array, where each document is single object containing all the data.",
	"Description5" => "Our system simply replaces specified keywords inside document text. Keywords are expected in format ",	
	"Description6" => "We also support multilevel objects in data. Simple use ",
	"Description7" => "You can also have repeated items inside your data. To insert them, you will need to write ",
	"Description8" => " on one line, where items is data keyword which contains multiple subitems, item is keyword that can be used inside foreach tag. Then place content that will be repeated for each item (that will be masked as ",
	"Description9" => " and then create ending line with ",
	"Description10" => "It's vitaly important, that both ",
	"Description11" => " tags must be on own line. Anything that will be on the same line will be deleted.",
	"Description12" => "Example data",	
	"Description13" => "To repeat table rows you need to write ",
	"Description14" => " to one row, then add rows that will contain items,	then add one last row with ",
	"Description15" => " that will mark end of the mutliitem replacing.",
	"Description16" => "This is how your document would look like",
	"Description17" => "Our generator supports some basic filters you can use on values. The syntax is ",
	"Description18" => "If you use as a parameter string value, you have to enclose it in ",
	"Description19" => "for example ",
	"Description20" => "You can also use variables from your temlate data. For example if you have variable named ",
	"Description21" => "Use this filter to format a date value to a specific format. For supported formats see ",
	"Description22" => "available formats",
	"Description23" => "As a date value you can use a unix timestamp or dates supported by PHP function ",
	"Description24" => "To convert strings to uppercase letters use ",
	"Description25" => " For lowercase letters use ",
	"Description26" => "With this filter you can use EU format or US format by default. Usage id ",

];

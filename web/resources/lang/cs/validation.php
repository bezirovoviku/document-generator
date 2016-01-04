<?php

return [

	/*
	|--------------------------------------------------------------------------
	| Validation Language Lines
	|--------------------------------------------------------------------------
	|
	| The following language lines contain the default error messages used by
	| the validator class. Some of these rules have multiple versions such
	| as the size rules. Feel free to tweak each of these messages here.
	|
	*/

	"accepted"             => ":attribute musí být přijmut.",
	"active_url"           => ":attribute není platná URL.",
	"after"                => ":attribute musí být datum po :date.",
	"alpha"                => ":attribute musí obsahovat pouze písmena.",
	"alpha_dash"           => ":attribute musí obsahovat pouze písmena, čísla, a pomlčky.",
	"alpha_num"            => ":attribute musí obsahovat pouze písmena, čísla.",
	"array"                => ":attribute musí být pole.",
	"before"               => ":attribute must být datum po :date.",
	"between"              => [
		"numeric" => ":attribute musí být mezi :min and :max.",
		"file"    => ":attribute musí být mezi :min and :max kilobyty.",
		"string"  => ":attribute musí být mezi :min and :max znaků.",
		"array"   => ":attribute musí mít meti :min and :max položek.",
	],
	"boolean"              => ":attribute musí být potvrzen nebo zamítnut.",
	"confirmed"            => ":attribute se neschoduje.",
	"date"                 => ":attribute není platné datum.",
	"date_format"          => ":attribute nesplňuje formát :format.",
	"different"            => ":attribute a :other se musí lišit.",
	"digits"               => ":attribute musí mít :digits číslic.",
	"digits_between"       => ":attribute musí mít mezi :min a :max číslic.",
	"email"                => ":attribute musí být platná adresa.",
	"filled"               => ":attribute je požadován.",
	"exists"               => ":attribute již existuje.",
	"image"                => ":attribute musí být číslo.",
	"in"                   => ":attribute není platný.",
	"integer"              => ":attribute musí být integer.",
	"ip"                   => ":attribute musí být platná IP address.",
	"max"                  => [
		"numeric" => ":attribute by neměl být větší než :max.",
		"file"    => ":attribute by neměl být větší než :max kilobytů.",
		"string"  => ":attribute by neměl být větší než :max znaků.",
		"array"   => ":attribute may not have more than :max položek.",
	],
	"mimes"                => "The :attribute must be a file of type: :values.",
	"min"                  => [
		"numeric" => ":attribute musí být minimálně :min.",
		"file"    => ":attribute musí být minimálně :min kilobytů.",
		"string"  => ":attribute musí být minimálně :min znaků.",
		"array"   => ":attribute musí být minimálně :min položek.",
	],
	"not_in"               => ":attribute je neplatný.",
	"numeric"              => ":attribute musí být číslo.",
	"regex"                => ":attribute formát je neplatný.",
	"required"             => "Položka :attribute je třeba vyplnit.",
	"required_if"          => "The :attribute field is required when :other is :value.",
	"required_with"        => "The :attribute field is required when :values is present.",
	"required_with_all"    => "The :attribute field is required when :values is present.",
	"required_without"     => "The :attribute field is required when :values is not present.",
	"required_without_all" => "The :attribute field is required when none of :values are present.",
	"same"                 => ":attribute a :other se musí schodovat.",
	"size"                 => [
		"numeric" => ":attribute musí být :size.",
		"file"    => ":attribute musí být :size kilobytů.",
		"string"  => ":attribute musí být :size znaků.",
		"array"   => ":attribute musí obsahovat :size položek.",
	],
	"unique"               => ":attribute je již obsazen.",
	"url"                  => ":attribute formát je neplatný.",
	"timezone"             => ":attribute musí být v platném rozpětí.",

	/*
	|--------------------------------------------------------------------------
	| Custom Validation Language Lines
	|--------------------------------------------------------------------------
	|
	| Here you may specify custom validation messages for attributes using the
	| convention "attribute.rule" to name the lines. This makes it quick to
	| specify a specific custom language line for a given attribute rule.
	|
	*/

	'custom' => [
		'attribute-name' => [
			'rule-name' => 'custom-message',
		],
	],

	/*
	|--------------------------------------------------------------------------
	| Custom Validation Attributes
	|--------------------------------------------------------------------------
	|
	| The following language lines are used to swap attribute place-holders
	| with something more reader friendly such as E-Mail Address instead
	| of "email". This simply helps us make messages a little cleaner.
	|
	*/

	'attributes' => [
		'username'							=> 'uživatelské jméno',
		'user' 								=> 'uživatel',
		'password_confirmation' 		=> 'Potvrzení hesla',
		'email'						 		=> 'Email',
		'password'					 		=> 'Heslo',
]

];

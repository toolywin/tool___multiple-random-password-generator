<?php
if( !defined('PSZAPP_INIT') ) exit;

// each log type must be unique number, each tool should have 10 types
$PSZ_LOG_MULTIPLE_PWD_GENERATOR_RANDOMIZE = 520;
$PSZ_LOG_MULTIPLE_PWD_GENERATOR_MANUAL = 521;

/********************************
Required Files:
	index.php		: where to process your tool
	settings.php	: global settings of your tool
	logos			: folder to contain various logos of your tool
		16.png			: to display on the browser's title bar
		180.png			: tool logo
	sharing.jpg		: used to sharing on socials

$tool_settings		:	Unchangable variable name
	Version			:	Required
	Name			:	Required
	Description		:	Required
	Keyword			:	optional
	Developer		:	Required
		Name			:	Required
		Contact			:	Required, website or email
		Source			:	optional, links to open source sites
		Donate			:	optional, links to donations
			Paypal			:	link to paypal donation
			BTC				:	link to BTC donation
			Ethereum		:	link to ETH donation
	Date			:	Required, created date; format: Y-MM-d, 2022-11-17

	Changelog		:	optional - used to store changelog
********************************/

$tool_settings = [
	'Version'     => '1.0.0',
	'Name'        => __('Multiple Random Password Generator'),
	'Description' => __('Instantly generate random series of highly secure passwords to use for your online credentials, scan QR code to send to your phone immediately and share your selected criteria to your friends for better safe. You may also use the memmorable words to remember the password easier & better.'),
	'Keyword'     => __('best random password generator, secure password generation tool, fast and efficient password generators, automated password generator tools, strong and complex password generators, unique password generator software, mass password generator tool, online random password generator, multiple random password generator, batch password generator, simultaneous password maker, password generation in bulk, memmorable words random passwords, random passwords qrcode'),
	'Developer'   => [
		'Name'    	=> 'PreScriptZ.com',
		'Contact' 	=> 'https://www.prescriptz.com/',
		'Source'	=> [
			'GitHub'    => 'https://github.com/toolywin/tool___' . basename(__DIR__),
		],
		'Donate'  	=> [
			'Paypal'   => 'https://www.paypal.me/PREScriptZ',
			'BTC'      => 'https://blockchain.info/address/1FNvqxG5T6P5UFtLvq5hdGir6LnS1zJQ6m',
			'Ethereum' => 'https://etherscan.io/address/0x85469855fd24498418e58ff9ad0298f0c498b4e8',
			'LTC'      => 'https://live.blockcypher.com/ltc/address/LY6ADMcfUejoeExifh2ngMXpHM5z8CXxuq',
		],
	],
	'Date'      => '2023-01-31', // created date
	//'Changelog' => [],
];
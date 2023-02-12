<?php
if( !defined('PSZAPP_INIT') ) exit;

// exit if POST because this tool does not support POST
if( $is_POST ) exit;

// log action only
if( !$is_bot && isset($_GET['do']) && in_array($_GET['do'], ['generate', 'randomize']) )
{
	// increase usage count
	$db->_increase($PSZ_TABLE_TOOL, 'count', "id=$tool_id");

	// process parameters
	$input = '';
	$log_task = $PSZ_LOG_MULTIPLE_PWD_GENERATOR_RANDOMIZE;
	if( $_GET['do']=='generate' )
	{
		$log_task = $PSZ_LOG_MULTIPLE_PWD_GENERATOR_MANUAL;
		$input = [];
		foreach ($_GET as $key => $value) {
			if( !in_array($key, ['uppercase', 'lowercase', 'number', 'symbol', 'length', 'quantity']) )
				continue;

			$input[$key] = $value;
		}

		// convert to string
		$input = http_build_query($input);
	}

	_log_tool($log_task, $user_session!=NULL?$user_session['id']:0, $tool_id, '', $input);
	exit;
}

// change tool settings
if( isset($_GET['act']) && $_GET['act']=='setting' )
{
	include_once('configurations.php');
	exit;
}

// tool settings default values
$t_settings             = unserialize($db_tool_config['settings']);
$t_setting_number_log   = isset($t_settings['number']) ? $t_settings['number'] : 15;
$t_setting_randomize    = isset($t_settings['randomize']) ? $t_settings['randomize'] : 5;
$t_setting_max_length   = isset($t_settings['length']) ? $t_settings['length'] : 64;
$t_setting_max_quantity = isset($t_settings['quantity']) ? $t_settings['quantity'] : 15;

// default user values
$check_upper = $check_lower = true;
$check_number = $check_symbol = false;
$user_length = 12;
$user_quantity = 5;

// detect parameters
$parameters = $pattern = '';

if( isset($_GET['uppercase']) && $_GET['uppercase'] )
{
	$check_upper = true;
	$parameters .= "uppercase=1&";
	$pattern .= 'A';
}
if( isset($_GET['lowercase']) && $_GET['lowercase'] )
{
	$check_lower = true;
	$parameters .= "lowercase=1&";
	$pattern .= 'a';
}
if( isset($_GET['number']) && $_GET['number'] )
{
	$check_number = true;
	$parameters .= "number=1&";
	$pattern .= '1';
}
if( isset($_GET['symbol']) && $_GET['symbol'] )
{
	$check_symbol = true;
	$parameters .= "symbol=1&";
	$pattern .= '$';
}
if( isset($_GET['length']) && $_GET['length']>=5 && $_GET['length']<=$t_setting_max_length )
{
	$user_length = $_GET['length'];
	$parameters .= "length=$_GET[length]&";
}
if( isset($_GET['quantity']) && $_GET['quantity']>=1 && $_GET['quantity']<=$t_setting_max_quantity )
{
	$user_quantity = $_GET['quantity'];
	$parameters .= "quantity=$_GET[quantity]&";
}

if( $pattern!='' )
	$pattern = "[$pattern]";

$url_share .= $parameters!='' ? "?" . trim($parameters, '&') : '';
//die($parameters);

// only change meta tags if has parameters
if( $parameters!='' )
{
	$page_title       = "$user_quantity " . __('random') . " $user_length-" . __('char') . "$pattern " . ($user_quantity==1 ? __('password') : __('passwords')) . " - $page_title";
	$page_description = "$page_title. " . $tool_settings['Description'];
}

//print_r($_GET);exit;

// show usage logs
if( NULL != ($rows=$db->_fetchAll($PSZ_TABLE_TOOL_USAGE_LOG, 'id, input, log_type, time', "tool_id=$tool_id AND private=0", "ORDER BY id DESC LIMIT 0,$t_setting_number_log")) )
{
	foreach ($rows as $r)
	{
		$type = __('random passwords');
		$input = trim($r['input']);
		if( $input!='' ) {
			$pattern = '';

			// parse string to array
			parse_str($input, $para);

			// prepend ? to URL
			$input = "?$input";

			$type = "$para[quantity] $para[length]-" . __('char');

			if( isset($para['uppercase']) )
				$pattern .= 'A';
			if( isset($para['lowercase']) )
				$pattern .= 'a';
			if( isset($para['number']) )
				$pattern .= '1';
			if( isset($para['symbol']) )
				$pattern .= '$';
			if( $pattern!='' )
				$pattern = "[$pattern]";

			$type .= "$pattern " . ($para['quantity']==1 ? __('password') : __('passwords'));
		}

		$pTemplate->assign_block_vars('log', [
			'TYPE'       => $type,
			'INPUT'      => $input,
			'TIME'       => time2str($r['time']),
			'TIME_ALT'   => date('d ', $r['time']) . __(date('M', $r['time'])) . date(', Y h:i:s', $r['time']),
		]);
	}
}

// common vars
$pTemplate->assign_vars([
	'RANDOMIZE'     => $t_setting_randomize,
	'MAX_QUANTITY'  => $t_setting_max_quantity,
	'USER_QUANTITY' => $user_quantity,
	'MAX_LENGTH'    => $t_setting_max_length,
	'USER_LENGTH'   => $user_length,
	'CHECK_UPPER'   => $check_upper ? 'checked' : '',
	'CHECK_LOWER'   => $check_lower ? 'checked' : '',
	'CHECK_NUMBER'  => $check_number ? 'checked' : '',
	'CHECK_SYMBOL'  => $check_symbol ? 'checked' : '',
]);

// do not compress example codes
$donot_compress = true;
$pContent .= $pTemplate->include_file($PSZ_DIR_TOOL . "/$tool_slug/main.html");

$pContent = $pTemplate->pparse($pContent, false); // keep global vars
//$pContent = $pTemplate->pparse($pContent, true, true, true); // this will compress all HTML code, even compress do_not_compress sections
?>
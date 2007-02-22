<?php
/**
 * Roughly simulates the frontend although being in the backend.
 *
 * @return	void
 * @todo	This is a quick hack, needs proper implementation
 */
	// *******************************
	// Set error reporting
	// *******************************

	error_reporting (E_ALL ^ E_NOTICE);


	// ******************
	// Constants defined
	// ******************
	define('PATH_site', '/ws/wec/');
	
	if (@is_dir(PATH_site.'typo3/sysext/cms/tslib/')) {
		define('PATH_tslib', PATH_site.'typo3/sysext/cms/tslib/');
	} elseif (@is_dir(PATH_site.'tslib/')) {
		define('PATH_tslib', PATH_site.'tslib/');
	} else {

		// define path to tslib/ here:
		$configured_tslib_path = '';

		// example:
		// $configured_tslib_path = '/var/www/mysite/typo3/sysext/cms/tslib/';

		define('PATH_tslib', $configured_tslib_path);
	}
	if (PATH_tslib=='') {
		die('Cannot find tslib/. Please set path by defining $configured_tslib_path in '.basename(PATH_thisScript).'.');
	}

	// ******************
	// include TSFE
	// ******************

	require (PATH_tslib.'index_ts.php');

	// global $TSFE, $TYPO3_CONF_VARS;
	// 
	// // FIXME: Currently bad workaround which only initializes a few things, not really what you'd call a frontend enviroment
	// 
	// require_once(PATH_tslib.'class.tslib_fe.php');
	// require_once(PATH_t3lib.'class.t3lib_page.php');
	// require_once(PATH_t3lib.'class.t3lib_userauth.php');
	// require_once(PATH_tslib.'class.tslib_feuserauth.php');
	// require_once(PATH_t3lib.'class.t3lib_tstemplate.php');
	// require_once(PATH_t3lib.'class.t3lib_cs.php');
	// 
	// $temp_TSFEclassName = t3lib_div::makeInstanceClassName('tslib_fe');
	// $TSFE = new $temp_TSFEclassName(
	// 		$TYPO3_CONF_VARS,
	// 		t3lib_div::_GP('id'),
	// 		t3lib_div::_GP('type'),
	// 		t3lib_div::_GP('no_cache'),
	// 		t3lib_div::_GP('cHash'),
	// 		t3lib_div::_GP('jumpurl'),
	// 		t3lib_div::_GP('MP'),
	// 		t3lib_div::_GP('RDCT')
	// 	);
	// $TSFE->connectToDB();
	// $TSFE->config = array();		// Must be filled with actual config!

?>
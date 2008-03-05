<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');

require_once(t3lib_extMgm::extPath($_EXTKEY).'backend/class.tx_wecassessment_flexform.php');
require_once(t3lib_extMgm::extPath($_EXTKEY).'backend/class.tx_wecassessment_labels.php');

$confArr = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['wec_assessment']);

if(!$confArr['manualQuestionSorting']) {
	$enableManualQuestionSorting = false;
} else {
	$enableManualQuestionSorting = true;
}

t3lib_extMgm::allowTableOnStandardPages("tx_wecassessment_category");
$TCA["tx_wecassessment_category"] = Array (
	"ctrl" => Array (
		'title' => 'LLL:EXT:wec_assessment/locallang_db.xml:tx_wecassessment_category',		
		'label' => 'title',	
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		'versioningWS' => true, 
		'origUid' => 't3_origuid',
		"sortby" => "sorting",	
		"delete" => "deleted",	
		"treeParentField" => "parent_category",
		"enablecolumns" => Array (		
			"disabled" => "hidden",
		),
		"dynamicConfigFile" => t3lib_extMgm::extPath($_EXTKEY)."tca.php",
		"iconfile" => t3lib_extMgm::extRelPath($_EXTKEY)."icons/icon_tx_wecassessment_category.gif",
		"dividers2tabs" => true,
	),
	"feInterface" => Array (
		"fe_admin_fieldList" => "hidden, title, description, image, questions, recommendations",
	)
);


t3lib_extMgm::allowTableOnStandardPages("tx_wecassessment_question");
$TCA["tx_wecassessment_question"] = Array (
	"ctrl" => Array (
		'title' => 'LLL:EXT:wec_assessment/locallang_db.xml:tx_wecassessment_question',		
		'label' => 'text',	
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		'versioningWS' => true, 
		'origUid' => 't3_origuid',
		"sortby" => "sorting",	
		"delete" => "deleted",	
		"enablecolumns" => Array (		
			"disabled" => "hidden",
		),
		"dynamicConfigFile" => t3lib_extMgm::extPath($_EXTKEY)."tca.php",
		"iconfile" => t3lib_extMgm::extRelPath($_EXTKEY)."icons/icon_tx_wecassessment_question.gif",
		"dividers2tabs" => true,
	),
	"feInterface" => Array (
		"fe_admin_fieldList" => "hidden, category_id, text, weight",
	)
);

if(!$enableManualQuestionSorting) {
	unset($TCA["tx_wecassessment_question"]["ctrl"]["sortby"]);
	$TCA["tx_wecassessment_question"]["ctrl"]["default_sortby"] = "ORDER BY text ASC";
}


t3lib_extMgm::allowTableOnStandardPages("tx_wecassessment_answer");
$TCA["tx_wecassessment_answer"] = Array (
	"ctrl" => Array (
		'title' => 'LLL:EXT:wec_assessment/locallang_db.xml:tx_wecassessment_answer',		
		'label' => 'result_id,question_id,value',
		'label_alt' => 'result_id,question_id,value',
		'label_alt_force' => true,	
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		'versioningWS' => true, 
		'origUid' => 't3_origuid',
		"default_sortby" => "ORDER BY crdate DESC",	
		"delete" => "deleted",	
		"enablecolumns" => Array (		
			"disabled" => "hidden",
		),
		"dynamicConfigFile" => t3lib_extMgm::extPath($_EXTKEY)."tca.php",
		"iconfile" => t3lib_extMgm::extRelPath($_EXTKEY)."icons/icon_tx_wecassessment_answer.gif",
	),
	"feInterface" => Array (
		"fe_admin_fieldList" => "hidden, value, question_id, result_id",
	)
);

t3lib_extMgm::allowTableOnStandardPages("tx_wecassessment_result");
$TCA["tx_wecassessment_result"] = Array (
	"ctrl" => Array (
		'title' => 'LLL:EXT:wec_assessment/locallang_db.xml:tx_wecassessment_result',		
		'label' => 'feuser_id',
		"label_alt" => 'uid',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		'type' => 'type',	
		'versioningWS' => true, 
		'origUid' => 't3_origuid',
		"default_sortby" => "ORDER BY crdate DESC",	
		"delete" => "deleted",	
		"enablecolumns" => Array (		
			"disabled" => "hidden",
		),
		"dynamicConfigFile" => t3lib_extMgm::extPath($_EXTKEY)."tca.php",
		"iconfile" => t3lib_extMgm::extRelPath($_EXTKEY)."icons/icon_tx_wecassessment_result.gif",
		"dividers2tabs" => true,
	),
	"feInterface" => Array (
		"fe_admin_fieldList" => "hidden, type, feuser_id",
	)
);


t3lib_extMgm::allowTableOnStandardPages("tx_wecassessment_recommendation");
$TCA["tx_wecassessment_recommendation"] = Array (
	"ctrl" => Array (
		'title' => 'LLL:EXT:wec_assessment/locallang_db.xml:tx_wecassessment_recommendation',		
		'label' => 'category_id',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		'versioningWS' => true, 
		'origUid' => 't3_origuid',
		"default_sortby" => "ORDER BY type, category_id, question_id, min_value, max_value",	
		"delete" => "deleted",
		'type' => 'type',
		"enablecolumns" => Array (		
			"disabled" => "hidden",
		),
		"dynamicConfigFile" => t3lib_extMgm::extPath($_EXTKEY)."tca.php",
		"iconfile" => t3lib_extMgm::extRelPath($_EXTKEY)."icons/icon_tx_wecassessment_recommendation.gif",
	),
	"feInterface" => Array (
		"fe_admin_fieldList" => "hidden, category_id, text, min_value, max_value",
	)
);


t3lib_div::loadTCA('tt_content');
$TCA['tt_content']['types']['list']['subtypes_excludelist'][$_EXTKEY.'_pi1']='layout,select_key,pages';
$TCA['tt_content']['types']['list']['subtypes_addlist'][$_EXTKEY.'_pi1']='pi_flexform';


t3lib_extMgm::addPlugin(array('LLL:EXT:wec_assessment/locallang_db.xml:tt_content.list_type_pi1', $_EXTKEY.'_pi1'),'list_type');
t3lib_extMgm::addPiFlexFormValue($_EXTKEY.'_pi1', 'FILE:EXT:wec_assessment/pi1/flexform_ds.xml');

/* enable label_userFunc only for TYPO3 v 4.1 and higher */
if (t3lib_div::int_from_ver(TYPO3_version) >= 4001000) {
	$TCA['tx_wecassessment_recommendation']['ctrl']['label_userFunc']="tx_wecassessment_labels->getRecommendationLabel";
	$TCA['tx_wecassessment_result']['ctrl']['label_userFunc']="tx_wecassessment_labels->getResultLabel";
	$TCA['tx_wecassessment_answer']['ctrl']['label_userFunc']="tx_wecassessment_labels->getAnswerLabel";
	$TCA['tx_wecassessment_category']['ctrl']['label_userFunc']="tx_wecassessment_labels->getCategoryLabel";
	$TCA['tx_wecassessment_question']['ctrl']['label_userFunc']="tx_wecassessment_labels->getQuestionLabel";
}


/* Adds wizard icon to the content element wizard. */
if (TYPO3_MODE=='BE')	{
	$TBE_MODULES_EXT['xMOD_db_new_content_el']['addElClasses']['tx_wecassessment_pi1_wizicon'] = t3lib_extMgm::extPath($_EXTKEY).'pi1/class.tx_wecassessment_pi1_wizicon.php';
}

/* Add CSH Labels */
t3lib_extMgm::addLLrefForTCAdescr('tx_wecassessment_category','EXT:wec_assessment/csh/locallang_csh_category.php');
t3lib_extMgm::addLLrefForTCAdescr('tx_wecassessment_question','EXT:wec_assessment/csh/locallang_csh_question.php');
t3lib_extMgm::addLLrefForTCAdescr('tx_wecassessment_recommendation','EXT:wec_assessment/csh/locallang_csh_recommendation.php');


/* Add Static templates */
t3lib_extMgm::addStaticFile($_EXTKEY,"pi1/static/ts/", "WEC Assessment");
t3lib_extMgm::addStaticFile($_EXTKEY,"pi1/static/css/", "WEC Assessment CSS Styles");

?>
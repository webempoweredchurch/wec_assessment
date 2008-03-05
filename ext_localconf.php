<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');

$confArr = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['wec_assessment']);

if(!intval($confArr['inlineEditing'])) {
	$enableInlineEditing = false;
} else {
	$enableInlineEditing = true;
}

/* Adds save and new buttons */
t3lib_extMgm::addUserTSConfig('options.saveDocNew.tx_wecassessment_category=1');
t3lib_extMgm::addUserTSConfig('options.saveDocNew.tx_wecassessment_question=1');
t3lib_extMgm::addUserTSConfig('options.saveDocNew.tx_wecassessment_answer=1');
t3lib_extMgm::addUserTSConfig('options.saveDocNew.tx_wecassessment_recommendation=1');

/* If inline editing is enabled, hide the answer table in list view */
if($enableInlineEditing) {
	t3lib_extMgm::addPageTSConfig('mod.web_list.hideTables := addToList(tx_wecassessment_answer)');
	
}

t3lib_extMgm::addPItoST43($_EXTKEY,'pi1/class.tx_wecassessment_pi1.php','_pi1','list_type',0);

/* Register hooks */
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass'][] = 'EXT:wec_assessment/backend/class.tx_wecassessment_tcemain_processdatamap.php:tx_wecassessment_tcemain_processdatamap';
$GLOBALS ['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tceforms.php']['getMainFieldsClass'][] = 'EXT:wec_assessment/backend/class.tx_wecassessment_tceforms_getmainfields.php:tx_wecassessment_tceforms_getmainfields';

?>
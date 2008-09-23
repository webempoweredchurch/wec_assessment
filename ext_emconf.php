<?php

########################################################################
# Extension Manager/Repository config file for ext: "wec_assessment"
#
# Auto generated 23-09-2008 13:23
#
# Manual updates:
# Only the data in the array - anything else is removed by next write.
# "version" and "dependencies" must not be touched!
########################################################################

$EM_CONF[$_EXTKEY] = array(
	'title' => 'WEC Assessment',
	'description' => 'Survey with customized recommendations based on answers. Includes sample surveys for sprititual growth and spiritual gifts.',
	'category' => 'plugin',
	'author' => 'Web-Empowered Church Team',
	'author_email' => 'assessment@webempoweredchurch.org',
	'shy' => '',
	'dependencies' => '',
	'conflicts' => '',
	'priority' => '',
	'module' => '',
	'state' => 'beta',
	'internal' => '',
	'uploadfolder' => 1,
	'createDirs' => '',
	'modify_tables' => '',
	'clearCacheOnLoad' => 1,
	'lockType' => '',
	'author_company' => 'Christian Technology Ministries International Inc.',
	'version' => '1.0.0',
	'constraints' => array(
		'depends' => array(
			'typo3' => '4.2.0-4.3.99',
			'wec_api' => '1.0.0-'
		),
		'conflicts' => array(
		),
		'suggests' => array(
		),
	),
	'_md5_values_when_last_written' => 'a:91:{s:21:"ext_conf_template.txt";s:4:"b5d8";s:12:"ext_icon.gif";s:4:"0ab7";s:17:"ext_localconf.php";s:4:"0a10";s:14:"ext_tables.php";s:4:"b9f5";s:14:"ext_tables.sql";s:4:"af2a";s:13:"locallang.xml";s:4:"f064";s:16:"locallang_db.xml";s:4:"1f87";s:7:"tca.php";s:4:"c705";s:43:"backend/class.tx_wecassessment_flexform.php";s:4:"4fa6";s:48:"backend/class.tx_wecassessment_itemsProcFunc.php";s:4:"eed3";s:41:"backend/class.tx_wecassessment_labels.php";s:4:"f908";s:42:"backend/class.tx_wecassessment_results.php";s:4:"da8a";s:57:"backend/class.tx_wecassessment_tceforms_getmainfields.php";s:4:"2bdc";s:57:"backend/class.tx_wecassessment_tcemain_processdatamap.php";s:4:"2ec3";s:39:"backend/class.tx_wecassessment_tsfe.php";s:4:"3940";s:28:"csh/locallang_csh_answer.xml";s:4:"c112";s:30:"csh/locallang_csh_category.xml";s:4:"a150";s:25:"csh/locallang_csh_pi1.xml";s:4:"0026";s:30:"csh/locallang_csh_question.xml";s:4:"d240";s:36:"csh/locallang_csh_recommendation.xml";s:4:"bb3e";s:28:"csh/locallang_csh_result.xml";s:4:"5bbb";s:14:"doc/manual.sxw";s:4:"bed3";s:38:"icons/icon_tx_wecassessment_answer.gif";s:4:"b6f4";s:41:"icons/icon_tx_wecassessment_answer__d.gif";s:4:"eca0";s:41:"icons/icon_tx_wecassessment_answer__h.gif";s:4:"3fe5";s:41:"icons/icon_tx_wecassessment_answer__x.gif";s:4:"5b60";s:40:"icons/icon_tx_wecassessment_category.gif";s:4:"0fd9";s:43:"icons/icon_tx_wecassessment_category__d.gif";s:4:"165f";s:43:"icons/icon_tx_wecassessment_category__h.gif";s:4:"9a32";s:43:"icons/icon_tx_wecassessment_category__x.gif";s:4:"4752";s:40:"icons/icon_tx_wecassessment_question.gif";s:4:"0cc5";s:43:"icons/icon_tx_wecassessment_question__d.gif";s:4:"f48e";s:43:"icons/icon_tx_wecassessment_question__h.gif";s:4:"fad3";s:43:"icons/icon_tx_wecassessment_question__x.gif";s:4:"94f6";s:46:"icons/icon_tx_wecassessment_recommendation.gif";s:4:"89ec";s:49:"icons/icon_tx_wecassessment_recommendation__d.gif";s:4:"2ca2";s:49:"icons/icon_tx_wecassessment_recommendation__h.gif";s:4:"d2fc";s:49:"icons/icon_tx_wecassessment_recommendation__x.gif";s:4:"8c81";s:38:"icons/icon_tx_wecassessment_result.gif";s:4:"94c5";s:41:"icons/icon_tx_wecassessment_result__d.gif";s:4:"f0aa";s:41:"icons/icon_tx_wecassessment_result__h.gif";s:4:"5cde";s:41:"icons/icon_tx_wecassessment_result__x.gif";s:4:"5653";s:39:"model/class.tx_wecassessment_answer.php";s:4:"3258";s:43:"model/class.tx_wecassessment_assessment.php";s:4:"a6a2";s:41:"model/class.tx_wecassessment_category.php";s:4:"77ef";s:42:"model/class.tx_wecassessment_modelbase.php";s:4:"bf8b";s:41:"model/class.tx_wecassessment_question.php";s:4:"4d9d";s:47:"model/class.tx_wecassessment_recommendation.php";s:4:"37a7";s:58:"model/class.tx_wecassessment_recommendation_assessment.php";s:4:"8e31";s:56:"model/class.tx_wecassessment_recommendation_category.php";s:4:"168d";s:56:"model/class.tx_wecassessment_recommendation_question.php";s:4:"80b6";s:56:"model/class.tx_wecassessment_recommendationcontainer.php";s:4:"0c81";s:39:"model/class.tx_wecassessment_result.php";s:4:"e0e6";s:17:"pi1/assessment.js";s:4:"428c";s:19:"pi1/assessment.tmpl";s:4:"b6ae";s:14:"pi1/ce_wiz.gif";s:4:"4d12";s:34:"pi1/class.tx_wecassessment_pi1.php";s:4:"8a1e";s:42:"pi1/class.tx_wecassessment_pi1_wizicon.php";s:4:"632c";s:42:"pi1/class.tx_wecassessment_sessiondata.php";s:4:"6764";s:35:"pi1/class.tx_wecassessment_util.php";s:4:"f6b2";s:19:"pi1/flexform_ds.xml";s:4:"f04b";s:17:"pi1/locallang.xml";s:4:"993b";s:23:"pi1/res/assessment.tmpl";s:4:"563e";s:30:"pi1/res/assessment_slider.tmpl";s:4:"59d1";s:18:"pi1/res/styles.css";s:4:"0521";s:29:"pi1/res/images/handle-ovr.gif";s:4:"1052";s:25:"pi1/res/images/handle.gif";s:4:"33e5";s:32:"pi1/res/images/nextarrow-ovr.gif";s:4:"2f78";s:28:"pi1/res/images/nextarrow.gif";s:4:"474d";s:32:"pi1/res/images/prevarrow-ovr.gif";s:4:"92fa";s:28:"pi1/res/images/prevarrow.gif";s:4:"9f78";s:29:"pi1/res/images/questionbg.jpg";s:4:"c7a9";s:28:"pi1/res/images/slider-bg.gif";s:4:"1f22";s:43:"pi1/res/images/slider-images-track-left.png";s:4:"989b";s:44:"pi1/res/images/slider-images-track-right.png";s:4:"f5f5";s:29:"pi1/res/images/submit-ovr.gif";s:4:"5739";s:25:"pi1/res/images/submit.gif";s:4:"927c";s:24:"pi1/res/js/assessment.js";s:4:"4be2";s:31:"pi1/res/js/assessment_slider.js";s:4:"fa41";s:20:"pi1/res/js/glider.js";s:4:"0523";s:23:"pi1/static/ts/setup.txt";s:4:"a0dc";s:23:"t3d/self_assessment.t3d";s:4:"bfda";s:23:"t3d/spiritual_gifts.t3d";s:4:"1111";s:20:"tests/answerTest.php";s:4:"731a";s:15:"tests/build.xml";s:4:"d1db";s:22:"tests/categoryTest.php";s:4:"ab21";s:25:"tests/coverage-frames.xsl";s:4:"ea16";s:19:"tests/initTYPO3.php";s:4:"3025";s:25:"tests/phpunit2-frames.xsl";s:4:"adcf";s:22:"tests/questionTest.php";s:4:"6f6b";s:22:"tests/responseTest.php";s:4:"9c85";}',
	'suggests' => array(
	),
);

?>
<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');

$confArr = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['wec_assessment']);
if(!intval($confArr['inlineEditing'])) {
	$enableInlineEditing = false;
} else {
	$enableInlineEditing = true;
}

$TCA["tx_wecassessment_category"] = Array (
	"ctrl" => $TCA["tx_wecassessment_category"]["ctrl"],
	"interface" => Array (
		"showRecordFieldList" => "hidden,title,description,image"
	),
	"feInterface" => $TCA["tx_wecassessment_category"]["feInterface"],
	"columns" => Array (
		"hidden" => Array (		
			"exclude" => 1,
			"label" => "LLL:EXT:lang/locallang_general.xml:LGL.hidden",
			"config" => Array (
				"type" => "check",
				"default" => "0"
			)
		),
		"title" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:wec_assessment/locallang_db.xml:tx_wecassessment_category.title",		
			"config" => Array (
				"type" => "input",	
				"size" => "30",
			)
		),
		"description" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:wec_assessment/locallang_db.xml:tx_wecassessment_category.description",		
			"config" => Array (
				"type" => "text",
				"cols" => "30",
				"rows" => "5",
				"wizards" => Array(
					"_PADDING" => 2,
					"RTE" => Array(
						"notNewRecords" => 1,
						"RTEonly" => 1,
						"type" => "script",
						"title" => "Full screen Rich Text Editing|Formatteret redigering i hele vinduet",
						"icon" => "wizard_rte2.gif",
						"script" => "wizard_rte.php",
					),
				),
			)
		),
		"image" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:wec_assessment/locallang_db.xml:tx_wecassessment_category.image",		
			"config" => Array (
				"type" => "group",
				"internal_type" => "file",
				"allowed" => $GLOBALS["TYPO3_CONF_VARS"]["GFX"]["imagefile_ext"],	
				"max_size" => 500,	
				"uploadfolder" => "uploads/tx_wecassessment",
				"show_thumbs" => 1,	
				"size" => 1,	
				"minitems" => 0,
				"maxitems" => 1,
			)
		),

		"questions" => Array (
			"exclude" => 1,
			"label" => "Questions",
			"config" => Array (
				"type" => "inline",
				"foreign_table" => "tx_wecassessment_question",
				"foreign_field" => "category_id",
				"appearance" => Array (
					"collapseAll" => false,
					"expandSingle" => false,
					"newRecordLinkAddTitle" => true,
					"newRecordLinkPosition" => "bottom",
				),
			),
		),
		
		"recommendations" => Array (
			"exclude" => 1,
			"label" => "Recommendations",
			"config" => Array (
				"type" => "inline",
				"foreign_table" => "tx_wecassessment_recommendation",
				"foreign_field" => "category_id",
				"appearance" => Array (
					"collapseAll" => false,
					"expandSingle" => false,
					"newRecordLinkAddTitle" => true,
					"newRecordLinkPosition" => "bottom",
				),
			),
		),
				
	),
	"types" => Array (
		"0" => Array("showitem" => "--div--;Main,hidden;;1;;1-1-1, title;;;;2-2-2, description;;;richtext:rte_transform[mode=ts];3-3-3, image,--div--;Questions, questions, --div--;Recommendations, recommendations")
	),
	"palettes" => Array (
		"1" => Array("showitem" => "")
	)
);

if(!$enableInlineEditing) {
	$TCA['tx_wecassessment_category']['columns']['questions']['config']['type'] = 'passthrough';
	$TCA['tx_wecassessment_category']['columns']['recommendations']['config']['type'] = 'passthrough';	
}



$TCA["tx_wecassessment_question"] = Array (
	"ctrl" => $TCA["tx_wecassessment_question"]["ctrl"],
	"interface" => Array (
		"showRecordFieldList" => "hidden,text,category_id,weight"
	),
	"feInterface" => $TCA["tx_wecassessment_question"]["feInterface"],
	"columns" => Array (
		"hidden" => Array (		
			"exclude" => 1,
			"label" => "LLL:EXT:lang/locallang_general.xml:LGL.hidden",
			"config" => Array (
				"type" => "check",
				"default" => "0"
			)
		),
		"text" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:wec_assessment/locallang_db.xml:tx_wecassessment_question.text",		
			"config" => Array (
				"type" => "text",
				"cols" => "40",
				"rows" => "6",
				"wizards" => Array(
					"_PADDING" => 4,
					"RTE" => Array(
						"notNewRecords" => 1,
						"RTEonly" => 1,
						"type" => "script",
						"title" => "LLL:EXT:cms/locallang_ttc.php:bodytext.W.RTE",
						"icon" => "wizard_rte2.gif",
						"script" => "wizard_rte.php",
					),
				),
			)
		),
		"category_id" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:wec_assessment/locallang_db.xml:tx_wecassessment_question.category_id",		
			"config" => Array (
				"type" => "select",	
				"foreign_table" => "tx_wecassessment_category",
				"foreign_table_where" => "AND tx_wecassessment_category.pid=###CURRENT_PID### ORDER BY tx_wecassessment_category.title ASC",	
				"size" => 1,	
				"minitems" => 0,
				"maxitems" => 1,	
				"wizards" => Array(
					"_PADDING" => 2,
					"_VERTICAL" => 1,
					"add" => Array(
						"type" => "script",
						"title" => "Create new record",
						"icon" => "add.gif",
						"params" => Array(
							"table"=>"tx_wecassessment_category",
							"pid" => "###CURRENT_PID###",
							"setValue" => "prepend"
						),
						"script" => "wizard_add.php",
					),
					"list" => Array(
						"type" => "script",
						"title" => "List",
						"icon" => "list.gif",
						"params" => Array(
							"table"=>"tx_wecassessment_category",
							"pid" => "###CURRENT_PID###",
						),
						"script" => "wizard_list.php",
					),
					"edit" => Array(
						"type" => "popup",
						"title" => "Edit",
						"script" => "wizard_edit.php",
						"popup_onlyOpenIfSelected" => 1,
						"icon" => "edit2.gif",
						"JSopenParams" => "height=350,width=580,status=0,menubar=0,scrollbars=1",
					),
				),
			)
		),
		"weight" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:wec_assessment/locallang_db.xml:tx_wecassessment_question.weight",		
			"config" => Array (
				"type" => "input",
				"size" => "4",
				"max" => "4",
				"eval" => "double",
				"default" => 1
			)
		),
		"average_score" => Array(
			"exclude" => 1,
			"label" => "Average Score",
			"config" => Array(
				"type" => "passthrough",
				"form_type" => "user",
				"userFunc" => 'EXT:wec_assessment/backend/class.tx_wecassessment_results.php:tx_wecassessment_results->displayAverageForQuestion',
			),
		),
		"recommendations" => Array (
			"exclude" => 1,
			"label" => "Recommendations",
			"config" => Array (
				"type" => "inline",
				"foreign_table" => "tx_wecassessment_recommendation",
				"foreign_field" => "question_id",
				"appearance" => Array (
					"collapseAll" => false,
					"expandSingle" => false,
					"newRecordLinkAddTitle" => true,
					"newRecordLinkPosition" => "bottom",
				),
			),
		),
	),
	"types" => Array (
		"0" => Array("showitem" => "hidden;;1;;1-1-1, category_id,text;;;richtext:rte_transform[mode=ts];3-3-3, weight, average_score,--div--;Recommendations, recommendations")
	),
	"palettes" => Array (
		"1" => Array("showitem" => "")
	)
);

if(!$enableInlineEditing) {
	$TCA['tx_wecassessment_question']['columns']['recommendations']['config']['type'] = 'passthrough';	
}


$TCA["tx_wecassessment_answer"] = Array (
	"ctrl" => $TCA["tx_wecassessment_answer"]["ctrl"],
	"interface" => Array (
		"showRecordFieldList" => "hidden,value,question_id,result_id"
	),
	"feInterface" => $TCA["tx_wecassessment_answer"]["feInterface"],
	"columns" => Array (
		"hidden" => Array (		
			"exclude" => 1,
			"label" => "LLL:EXT:lang/locallang_general.xml:LGL.hidden",
			"config" => Array (
				"type" => "check",
				"default" => "0"
			)
		),
		"result_id" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:wec_assessment/locallang_db.xml:tx_wecassessment_answer.result_id",		
			"config" => Array (
				"type" => "select",	
				"foreign_table" => "tx_wecassessment_result",	
				"foreign_table_where" => "AND tx_wecassessment_result.pid=###CURRENT_PID### ORDER BY tx_wecassessment_result.uid",	
				"size" => 1,	
				"minitems" => 0,
				"maxitems" => 1,
			)
		),
		"question_id" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:wec_assessment/locallang_db.xml:tx_wecassessment_answer.question_id",		
			"config" => Array (
				"type" => "select",	
				"foreign_table" => "tx_wecassessment_question",	
				"foreign_table_where" => "AND tx_wecassessment_question.pid=###CURRENT_PID### ORDER BY tx_wecassessment_question.sorting",	
				"size" => 1,	
				"minitems" => 0,
				"maxitems" => 1,
			)
		),
		"value" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:wec_assessment/locallang_db.xml:tx_wecassessment_answer.value",		
			"config" => Array (
				"type" => "radio",
				"itemsProcFunc" => "EXT:wec_assessment/backend/class.tx_wecassessment_itemsProcFunc.php:tx_wecassessment_itemsProcFunc->getAnswerOptions"
			),
		),
	),
	"types" => Array (
		"0" => Array("showitem" => "hidden;;1;;1-1-1, result_id, question_id, value")
	),
	"palettes" => Array (
		"1" => Array("showitem" => "")
	)
);


$TCA["tx_wecassessment_result"] = Array (
	"ctrl" => $TCA["tx_wecassessment_result"]["ctrl"],
	"interface" => Array (
		"showRecordFieldList" => "hidden,type,feuser_id,assessment_id,details"
	),
	"feInterface" => $TCA["tx_wecassessment_result"]["feInterface"],
	"columns" => Array (
		"hidden" => Array (		
			"exclude" => 1,
			"label" => "LLL:EXT:lang/locallang_general.xml:LGL.hidden",
			"config" => Array (
				"type" => "check",
				"default" => "0"
			)
		),
		"type" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:wec_assessment/locallang_db.xml:tx_wecassessment_result.type",		
			"config" => Array (
				"type" => "select",
				"items" => Array (
					Array("LLL:EXT:wec_assessment/locallang_db.xml:tx_wecassessment_result.type.feuser.0", "0"),
					Array("LLL:EXT:wec_assessment/locallang_db.xml:tx_wecassessment_result.type.anonymous.1", "1"),
				),
				"size" => 1,	
				"maxitems" => 1,
			)
		),
		"feuser_id" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:wec_assessment/locallang_db.xml:tx_wecassessment_result.feuser_id",
			"config" => Array (
				"type" => "select",
				"items" => Array (
					Array('', 0),
				),
				"foreign_table" => "fe_users",
				"size" => 1,
				"minitems" => 0,
				"maxitems" => 1,
			),
		),
		/*
		"details" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:wec_assessment/locallang_db.xml:tx_wecassessment_result.details",
			"config" => Array (
				"type" => "user",
				"userFunc" => "tx_wecassessment_results->displayResults",
				"noTableWrapping" => true,
			),
		),
		*/
		"answers" => Array (
			"exclude" => 1,
			"label" => "Answers",
			"config" => Array (
				"type" => "inline",
				"foreign_table" => "tx_wecassessment_answer",
				"foreign_field" => "result_id",
				"appearance" => Array (
					"collapseAll" => false,
					"expandSingle" => false,
					"newRecordLinkAddTitle" => true,
					"newRecordLinkPosition" => "bottom",
				),
				"foreign_unique" => "question_id",
				"foreign_label" => "title",
			),
		),		
	),
	"types" => Array (
		"0" => Array("showitem" => "--div--;Overview,hidden;;1;;1-1-1, type, feuser_id, --div--;Answers, answers"),
		"1" => Array("showitem" => "--div--;Overview,hidden;;1;;1-1-1, type, --div--;Answers, answers"),
	),
	"palettes" => Array (
		"1" => Array("showitem" => "")
	)
);

if(!$enableInlineEditing) {
	$TCA['tx_wecassessment_result']['columns']['answers']['config']['type'] = 'passthrough';	
}

$TCA["tx_wecassessment_recommendation"] = Array (
	"ctrl" => $TCA["tx_wecassessment_recommendation"]["ctrl"],
	"interface" => Array (
		"showRecordFieldList" => "hidden,category_id,text,min_value,max_value"
	),
	"feInterface" => $TCA["tx_wecassessment_recommendation"]["feInterface"],
	"columns" => Array (
		"hidden" => Array (		
			"exclude" => 1,
			"label" => "LLL:EXT:lang/locallang_general.xml:LGL.hidden",
			"config" => Array (
				"type" => "check",
				"default" => "0"
			)
		),
		"type" => Array (
			"exclude" => 1,
			"label" => "LLL:EXT:wec_assessment/locallang_db.xml:tx_wecassessment_recommendation.type",
			"config" => Array (
				"type" => "select",
				"size" => 1,
				"minitems" => 0,
				"maxitems" => 1,
				"items" => Array (
					Array('Recommendation for Category', 0),
					Array('Recommendation for Question', 1),
					Array('Recommendation for Entire Assessment', 2),
				),
			),
		),
		"category_id" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:wec_assessment/locallang_db.xml:tx_wecassessment_recommendation.category_id",		
			"config" => Array (
				"type" => "select",	
				"foreign_table" => "tx_wecassessment_category",
				"foreign_table_where" => "AND tx_wecassessment_category.pid=###CURRENT_PID### ORDER BY tx_wecassessment_category.title ASC",	
				"size" => 1,	
				"minitems" => 0,
				"maxitems" => 1,
				/*	
				"wizards" => Array(
					"_PADDING" => 2,
					"_VERTICAL" => 1,
					"add" => Array(
						"type" => "script",
						"title" => "Create new record",
						"icon" => "add.gif",
						"params" => Array(
							"table"=>"tx_wecassessment_category",
							"pid" => "###CURRENT_PID###",
							"setValue" => "prepend"
						),
						"script" => "wizard_add.php",
					),
					"list" => Array(
						"type" => "script",
						"title" => "List",
						"icon" => "list.gif",
						"params" => Array(
							"table"=>"tx_wecassessment_category",
							"pid" => "###CURRENT_PID###",
						),
						"script" => "wizard_list.php",
					),
					"edit" => Array(
						"type" => "popup",
						"title" => "Edit",
						"script" => "wizard_edit.php",
						"popup_onlyOpenIfSelected" => 1,
						"icon" => "edit2.gif",
						"JSopenParams" => "height=350,width=580,status=0,menubar=0,scrollbars=1",
					),
				),
				*/
			)
		),
		
		"question_id" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:wec_assessment/locallang_db.xml:tx_wecassessment_recommendation.question_id",		
			"config" => Array (
				"type" => "select",	
				"foreign_table" => "tx_wecassessment_question",
				"foreign_table_where" => "AND tx_wecassessment_question.pid=###CURRENT_PID### ORDER BY tx_wecassessment_question.sorting ASC",
				"size" => 1,	
				"minitems" => 0,
				"maxitems" => 1,
				/*
				"wizards" => Array(
					"_PADDING" => 6,
					"_VERTICAL" => 0,
					"add" => Array(
						"type" => "script",
						"title" => "Create new record",
						"icon" => "add.gif",
						"params" => Array(
							"table"=>"tx_wecassessment_question",
							"pid" => "###CURRENT_PID###",
							"setValue" => "prepend"
						),
						"script" => "wizard_add.php",
					),
					"list" => Array(
						"type" => "script",
						"title" => "List",
						"icon" => "list.gif",
						"params" => Array(
							"table"=>"tx_wecassessment_question",
							"pid" => "###CURRENT_PID###",
						),
						"script" => "wizard_list.php",
					),
					"edit" => Array(
						"type" => "popup",
						"title" => "Edit",
						"script" => "wizard_edit.php",
						"popup_onlyOpenIfSelected" => 1,
						"icon" => "edit2.gif",
						"JSopenParams" => "height=350,width=580,status=0,menubar=0,scrollbars=1",
					),
				),
				*/
			)
		),

		"min_value" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:wec_assessment/locallang_db.xml:tx_wecassessment_recommendation.min_value",		
			"config" => Array (
				"type" => "input",
				"size" => "4",
				"max" => "4",
				"eval" => "double",
				"default" => 0,
			),
		),
		"max_value" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:wec_assessment/locallang_db.xml:tx_wecassessment_recommendation.max_value",		
			"config" => Array (
				"type" => "input",
				"size" => "4",
				"max" => "4",
				"eval" => "double",
				"default" => 0
			)
		),
		"text" => Array (		
			"exclude" => 1,		
			"label" => "LLL:EXT:wec_assessment/locallang_db.xml:tx_wecassessment_recommendation.text",		
			"config" => Array (
				"type" => "text",
				"cols" => "30",
				"rows" => "5",
				"wizards" => Array(
					"_PADDING" => 2,
					"RTE" => Array(
						"notNewRecords" => 1,
						"RTEonly" => 1,
						"type" => "script",
						"title" => "Full screen Rich Text Editing|Formatteret redigering i hele vinduet",
						"icon" => "wizard_rte2.gif",
						"script" => "wizard_rte.php",
					),
				),
			)
		),
	),
	"types" => Array (
		"0" => Array("showitem" => "hidden;;1;;1-1-1, type, category_id, min_value, max_value, text;;;richtext:rte_transform[mode=ts]"),
		"1" => Array("showitem" => "hidden;;1;;1-1-1, type, question_id, min_value, max_value, text;;;richtext:rte_transform[mode=ts]"),
		"2" => Array("showitem" => "hidden;;1;;1-1-1, type, min_value, max_value, text;;;richtext:rte_transform[mode=ts]")
	),
	"palettes" => Array (
		"1" => Array("showitem" => "")
	)
);
?>
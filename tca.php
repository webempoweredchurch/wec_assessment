<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');

$confArr = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['wec_assessment']);
if(!intval($confArr['inlineEditing'])) {
	$enableInlineEditing = false;
} else {
	$enableInlineEditing = true;
}

$TCA['tx_wecassessment_category'] = array(
	'ctrl' => $TCA['tx_wecassessment_category']['ctrl'],
	'interface' => array(
		'showRecordFieldList' => 'hidden,title,description,image'
	),
	'feInterface' => $TCA['tx_wecassessment_category']['feInterface'],
	'columns' => array(
		'hidden' => array(		
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.xml:LGL.hidden',
			'config' => array(
				'type' => 'check',
				'default' => '0'
			)
		),
		'title' => array(		
			'exclude' => 1,		
			'label' => 'LLL:EXT:wec_assessment/locallang_db.xml:tx_wecassessment_category.title',		
			'config' => array(
				'type' => 'input',	
				'size' => '30',
			)
		),
		'description' => array(		
			'exclude' => 1,		
			'label' => 'LLL:EXT:wec_assessment/locallang_db.xml:tx_wecassessment_category.description',		
			'config' => array(
				'type' => 'text',
				'cols' => '30',
				'rows' => '5',
				'wizards' => array(
					'_PADDING' => 2,
					'RTE' => array(
						'notNewRecords' => 1,
						'RTEonly' => 1,
						'type' => 'script',
						'title' => 'Full screen Rich Text Editing|Formatteret redigering i hele vinduet',
						'icon' => 'wizard_rte2.gif',
						'script' => 'wizard_rte.php',
					),
				),
			)
		),
		'image' => array(		
			'exclude' => 1,		
			'label' => 'LLL:EXT:wec_assessment/locallang_db.xml:tx_wecassessment_category.image',		
			'config' => array(
				'type' => 'group',
				'internal_type' => 'file',
				'allowed' => $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext'],	
				'max_size' => 500,	
				'uploadfolder' => 'uploads/tx_wecassessment',
				'show_thumbs' => 1,	
				'size' => 1,	
				'minitems' => 0,
				'maxitems' => 1,
			)
		),

		'questions' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:wec_assessment/locallang_db.xml:tx_wecassessment_category.questions',
			'config' => array(
				'type' => 'inline',
				'foreign_table' => 'tx_wecassessment_question',
				'foreign_field' => 'category_id',
				'appearance' => array(
					'collapseAll' => false,
					'expandSingle' => false,
					'newRecordLinkAddTitle' => true,
					'newRecordLinkPosition' => 'bottom',
				),
			),
		),
		
		'recommendations' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:wec_assessment/locallang_db.xml:tx_wecassessment_category.recommendations',
			'config' => array(
				'type' => 'inline',
				'foreign_table' => 'tx_wecassessment_recommendation',
				'foreign_field' => 'category_id',
				'appearance' => array(
					'collapseAll' => false,
					'expandSingle' => false,
					'newRecordLinkAddTitle' => true,
					'newRecordLinkPosition' => 'bottom',
				),
			),
		),
				
	),
	'types' => array(
		'0' => array('showitem' => '--div--;LLL:EXT:wec_assessment/locallang_db.xml:tx_wecassessment_tabs.general,hidden;;1;;1-1-1, title;;;;2-2-2, description;;;richtext:rte_transform[mode=ts];3-3-3, image,--div--;LLL:EXT:wec_assessment/locallang_db.xml:tx_wecassessment_tabs.questions, questions, --div--;LLL:EXT:wec_assessment/locallang_db.xml:tx_wecassessment_tabs.recommendations, recommendations')
	),
	'palettes' => array(
		'1' => array('showitem' => '')
	)
);

if(!$enableInlineEditing) {
	$TCA['tx_wecassessment_category']['columns']['questions']['config']['type'] = 'passthrough';
	$TCA['tx_wecassessment_category']['columns']['recommendations']['config']['type'] = 'passthrough';	
}



$TCA['tx_wecassessment_question'] = array(
	'ctrl' => $TCA['tx_wecassessment_question']['ctrl'],
	'interface' => array(
		'showRecordFieldList' => 'hidden,text,category_id,weight'
	),
	'feInterface' => $TCA['tx_wecassessment_question']['feInterface'],
	'columns' => array(
		'hidden' => array(		
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.xml:LGL.hidden',
			'config' => array(
				'type' => 'check',
				'default' => '0'
			)
		),
		'text' => array(		
			'exclude' => 1,		
			'label' => 'LLL:EXT:wec_assessment/locallang_db.xml:tx_wecassessment_question.text',		
			'config' => array(
				'type' => 'text',
				'cols' => '40',
				'rows' => '6',
				'wizards' => array(
					'_PADDING' => 4,
					'RTE' => array(
						'notNewRecords' => 1,
						'RTEonly' => 1,
						'type' => 'script',
						'title' => 'LLL:EXT:cms/locallang_ttc.php:bodytext.W.RTE',
						'icon' => 'wizard_rte2.gif',
						'script' => 'wizard_rte.php',
					),
				),
			)
		),
		'category_id' => array(		
			'exclude' => 1,		
			'label' => 'LLL:EXT:wec_assessment/locallang_db.xml:tx_wecassessment_question.category_id',		
			'config' => array(
				'type' => 'select',	
				'foreign_table' => 'tx_wecassessment_category',
				'foreign_table_where' => 'AND tx_wecassessment_category.pid=###CURRENT_PID### ORDER BY tx_wecassessment_category.title ASC',	
				'size' => 1,	
				'minitems' => 0,
				'maxitems' => 1,	
				'wizards' => array(
					'_PADDING' => 2,
					'_VERTICAL' => 1,
					'add' => array(
						'type' => 'script',
						'title' => 'Create new record',
						'icon' => 'add.gif',
						'params' => array(
							'table' => 'tx_wecassessment_category',
							'pid' => '###CURRENT_PID###',
							'setValue' => 'prepend'
						),
						'script' => 'wizard_add.php',
					),
					'list' => array(
						'type' => 'script',
						'title' => 'List',
						'icon' => 'list.gif',
						'params' => array(
							'table' => 'tx_wecassessment_category',
							'pid' => '###CURRENT_PID###',
						),
						'script' => 'wizard_list.php',
					),
					'edit' => array(
						'type' => 'popup',
						'title' => 'Edit',
						'script' => 'wizard_edit.php',
						'popup_onlyOpenIfSelected' => 1,
						'icon' => 'edit2.gif',
						'JSopenParams' => 'height=350,width=580,status=0,menubar=0,scrollbars=1',
					),
				),
			)
		),
		'weight' => array(		
			'exclude' => 1,		
			'label' => 'LLL:EXT:wec_assessment/locallang_db.xml:tx_wecassessment_question.weight',		
			'config' => array(
				'type' => 'input',
				'size' => '4',
				'max' => '4',
				'eval' => 'double',
				'default' => 1
			)
		),
		'average_score' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:wec_assessment/locallang_db.xml:tx_wecassessment_question.average_score',
			'config' => array(
				'type' => 'passthrough',
				'form_type' => 'user',
				'userFunc' => 'EXT:wec_assessment/backend/class.tx_wecassessment_results.php:tx_wecassessment_results->displayAverageForQuestion',
			),
		),
		'recommendations' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:wec_assessment/locallang_db.xml:tx_wecassessment_question.recommendations',
			'config' => array(
				'type' => 'inline',
				'foreign_table' => 'tx_wecassessment_recommendation',
				'foreign_field' => 'question_id',
				'appearance' => array(
					'collapseAll' => false,
					'expandSingle' => false,
					'newRecordLinkAddTitle' => true,
					'newRecordLinkPosition' => 'bottom',
				),
			),
		),
	),
	'types' => array(
		'0' => array('showitem' => 'hidden;;1;;1-1-1, category_id,text;;;richtext:rte_transform[mode=ts];3-3-3, weight, average_score,--div--;LLL:EXT:wec_assessment/locallang_db.xml:tx_wecassessment_tabs.recommendations, recommendations')
	),
	'palettes' => array(
		'1' => array('showitem' => '')
	)
);

if(!$enableInlineEditing) {
	$TCA['tx_wecassessment_question']['columns']['recommendations']['config']['type'] = 'passthrough';	
}


$TCA['tx_wecassessment_answer'] = array(
	'ctrl' => $TCA['tx_wecassessment_answer']['ctrl'],
	'interface' => array(
		'showRecordFieldList' => 'hidden,value,question_id,result_id'
	),
	'feInterface' => $TCA['tx_wecassessment_answer']['feInterface'],
	'columns' => array(
		'hidden' => array(		
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.xml:LGL.hidden',
			'config' => array(
				'type' => 'check',
				'default' => '0'
			)
		),
		'result_id' => array(		
			'exclude' => 1,		
			'label' => 'LLL:EXT:wec_assessment/locallang_db.xml:tx_wecassessment_answer.result_id',		
			'config' => array(
				'type' => 'select',	
				'foreign_table' => 'tx_wecassessment_result',	
				'foreign_table_where' => 'AND tx_wecassessment_result.pid=###CURRENT_PID### ORDER BY tx_wecassessment_result.uid',	
				'size' => 1,	
				'minitems' => 0,
				'maxitems' => 1,
			)
		),
		'question_id' => array(		
			'exclude' => 1,		
			'label' => 'LLL:EXT:wec_assessment/locallang_db.xml:tx_wecassessment_answer.question_id',		
			'config' => array(
				'type' => 'select',	
				'foreign_table' => 'tx_wecassessment_question',	
				'foreign_table_where' => 'AND tx_wecassessment_question.pid=###CURRENT_PID### ORDER BY tx_wecassessment_question.sorting',	
				'size' => 1,	
				'minitems' => 0,
				'maxitems' => 1,
			)
		),
		'value' => array(		
			'exclude' => 1,		
			'label' => 'LLL:EXT:wec_assessment/locallang_db.xml:tx_wecassessment_answer.value',		
			'config' => array(
				'type' => 'radio',
				'itemsProcFunc' => 'EXT:wec_assessment/backend/class.tx_wecassessment_itemsProcFunc.php:tx_wecassessment_itemsProcFunc->getAnswerOptions'
			),
		),
	),
	'types' => array(
		'0' => array('showitem' => 'hidden;;1;;1-1-1, result_id, question_id, value')
	),
	'palettes' => array(
		'1' => array('showitem' => '')
	)
);


$TCA['tx_wecassessment_result'] = array(
	'ctrl' => $TCA['tx_wecassessment_result']['ctrl'],
	'interface' => array(
		'showRecordFieldList' => 'hidden,type,feuser_id,assessment_id,details'
	),
	'feInterface' => $TCA['tx_wecassessment_result']['feInterface'],
	'columns' => array(
		'hidden' => array(		
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.xml:LGL.hidden',
			'config' => array(
				'type' => 'check',
				'default' => '0'
			)
		),
		'type' => array(		
			'exclude' => 1,		
			'label' => 'LLL:EXT:wec_assessment/locallang_db.xml:tx_wecassessment_result.type',		
			'config' => array(
				'type' => 'select',
				'items' => array(
					array('LLL:EXT:wec_assessment/locallang_db.xml:tx_wecassessment_result.type.feuser.0', '0'),
					array('LLL:EXT:wec_assessment/locallang_db.xml:tx_wecassessment_result.type.anonymous.1', '1'),
				),
				'size' => 1,	
				'maxitems' => 1,
			)
		),
		'feuser_id' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:wec_assessment/locallang_db.xml:tx_wecassessment_result.feuser_id',
			'config' => array(
				'type' => 'select',
				'items' => array(
					array('', 0),
				),
				'foreign_table' => 'fe_users',
				'size' => 1,
				'minitems' => 0,
				'maxitems' => 1,
			),
		),
		'answers' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:wec_assessment/locallang_db.xml:tx_wecassessment_result.answers',
			'config' => array(
				'type' => 'inline',
				'foreign_table' => 'tx_wecassessment_answer',
				'foreign_field' => 'result_id',
				'appearance' => array(
					'collapseAll' => false,
					'expandSingle' => false,
					'newRecordLinkAddTitle' => true,
					'newRecordLinkPosition' => 'bottom',
				),
				'foreign_unique' => 'question_id',
				'foreign_label' => 'title',
			),
		),		
	),
	'types' => array(
		'0' => array('showitem' => '--div--;LLL:EXT:wec_assessment/locallang_db.xml:tx_wecassessment_tabs.general,hidden;;1;;1-1-1, type, feuser_id, --div--;LLL:EXT:wec_assessment/locallang_db.xml:tx_wecassessment_tabs.answers, answers'),
		'1' => array('showitem' => '--div--;LLL:EXT:wec_assessment/locallang_db.xml:tx_wecassessment_tabs.general,hidden;;1;;1-1-1, type, --div--;LLL:EXT:wec_assessment/locallang_db.xml:tx_wecassessment_tabs.answers, answers'),
	),
	'palettes' => array(
		'1' => array('showitem' => '')
	)
);

if(!$enableInlineEditing) {
	$TCA['tx_wecassessment_result']['columns']['answers']['config']['type'] = 'passthrough';	
}

$TCA['tx_wecassessment_recommendation'] = array(
	'ctrl' => $TCA['tx_wecassessment_recommendation']['ctrl'],
	'interface' => array(
		'showRecordFieldList' => 'hidden,category_id,text,min_value,max_value'
	),
	'feInterface' => $TCA['tx_wecassessment_recommendation']['feInterface'],
	'columns' => array(
		'hidden' => array(		
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.xml:LGL.hidden',
			'config' => array(
				'type' => 'check',
				'default' => '0'
			)
		),
		'type' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:wec_assessment/locallang_db.xml:tx_wecassessment_recommendation.type',
			'config' => array(
				'type' => 'select',
				'size' => 1,
				'minitems' => 0,
				'maxitems' => 1,
				'items' => array(
					array('LLL:EXT:wec_assessment/locallang_db.xml:tx_wecassessment_recommendation.type.category', 0),
					array('LLL:EXT:wec_assessment/locallang_db.xml:tx_wecassessment_recommendation.type.question', 1),
					array('LLL:EXT:wec_assessment/locallang_db.xml:tx_wecassessment_recommendation.type.assessment', 2),
				),
			),
		),
		'category_id' => array(		
			'exclude' => 1,		
			'label' => 'LLL:EXT:wec_assessment/locallang_db.xml:tx_wecassessment_recommendation.category_id',		
			'config' => array(
				'type' => 'select',	
				'foreign_table' => 'tx_wecassessment_category',
				'foreign_table_where' => 'AND tx_wecassessment_category.pid=###CURRENT_PID### ORDER BY tx_wecassessment_category.title ASC',	
				'size' => 1,	
				'minitems' => 0,
				'maxitems' => 1,
			)
		),
		
		'question_id' => array(		
			'exclude' => 1,		
			'label' => 'LLL:EXT:wec_assessment/locallang_db.xml:tx_wecassessment_recommendation.question_id',		
			'config' => array(
				'type' => 'select',	
				'foreign_table' => 'tx_wecassessment_question',
				'foreign_table_where' => 'AND tx_wecassessment_question.pid=###CURRENT_PID### ORDER BY tx_wecassessment_question.sorting ASC',
				'size' => 1,	
				'minitems' => 0,
				'maxitems' => 1,
			)
		),

		'min_value' => array(		
			'exclude' => 1,		
			'label' => 'LLL:EXT:wec_assessment/locallang_db.xml:tx_wecassessment_recommendation.min_value',		
			'config' => array(
				'type' => 'input',
				'size' => '4',
				'max' => '4',
				'eval' => 'double',
				'default' => 0,
			),
		),
		'max_value' => array(		
			'exclude' => 1,		
			'label' => 'LLL:EXT:wec_assessment/locallang_db.xml:tx_wecassessment_recommendation.max_value',		
			'config' => array(
				'type' => 'input',
				'size' => '4',
				'max' => '4',
				'eval' => 'double',
				'default' => 0
			)
		),
		'text' => array(		
			'exclude' => 1,		
			'label' => 'LLL:EXT:wec_assessment/locallang_db.xml:tx_wecassessment_recommendation.text',		
			'config' => array(
				'type' => 'text',
				'cols' => '30',
				'rows' => '5',
				'wizards' => array(
					'_PADDING' => 2,
					'RTE' => array(
						'notNewRecords' => 1,
						'RTEonly' => 1,
						'type' => 'script',
						'title' => 'Full screen Rich Text Editing|Formatteret redigering i hele vinduet',
						'icon' => 'wizard_rte2.gif',
						'script' => 'wizard_rte.php',
					),
				),
			)
		),
	),
	'types' => array(
		'0' => array('showitem' => 'hidden;;1;;1-1-1, type, category_id, min_value, max_value, text;;;richtext:rte_transform[mode=ts]'),
		'1' => array('showitem' => 'hidden;;1;;1-1-1, type, question_id, min_value, max_value, text;;;richtext:rte_transform[mode=ts]'),
		'2' => array('showitem' => 'hidden;;1;;1-1-1, type, min_value, max_value, text;;;richtext:rte_transform[mode=ts]')
	),
	'palettes' => array(
		'1' => array('showitem' => '')
	)
);
?>
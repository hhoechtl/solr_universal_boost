<?php
if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}

$TCA['tx_solr_universal_boost_domain_model_boostkeyword'] = array(
	'ctrl' => $TCA['tx_solr_universal_boost_domain_model_boostkeyword']['ctrl'],
	'interface' => array(
		'showRecordFieldList' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden, ref_uid, keyword',
	),
	'types' => array(
		'1' => array('showitem' => 'sys_language_uid;;;;1-1-1, l10n_parent, l10n_diffsource, hidden;;1, ref_uid, keyword,--div--;LLL:EXT:cms/locallang_ttc.xlf:tabs.access,starttime, endtime'),
	),
	'palettes' => array(
		'1' => array('showitem' => ''),
	),
	'columns' => array(
		'sys_language_uid' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.language',
			'config' => array(
				'type' => 'select',
				'foreign_table' => 'sys_language',
				'foreign_table_where' => 'ORDER BY sys_language.title',
				'items' => array(
					array('LLL:EXT:lang/locallang_general.xlf:LGL.allLanguages', -1),
					array('LLL:EXT:lang/locallang_general.xlf:LGL.default_value', 0)
				),
			),
		),
		'l10n_parent' => array(
			'displayCond' => 'FIELD:sys_language_uid:>:0',
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.l18n_parent',
			'config' => array(
				'type' => 'select',
				'items' => array(
					array('', 0),
				),
				'foreign_table' => 'tx_solr_universal_boost_domain_model_boostkeyword',
				'foreign_table_where' => 'AND tx_solr_universal_boost_domain_model_boostkeyword.pid=###CURRENT_PID### AND tx_solr_universal_boost_domain_model_boostkeyword.sys_language_uid IN (-1,0)',
			),
		),
		'l10n_diffsource' => array(
			'config' => array(
				'type' => 'passthrough',
			),
		),
		't3ver_label' => array(
			'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.versionLabel',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'max' => 255,
			)
		),
		'hidden' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.hidden',
			'config' => array(
				'type' => 'check',
			),
		),
		'starttime' => array(
			'exclude' => 1,
			'l10n_mode' => 'mergeIfNotBlank',
			'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.starttime',
			'config' => array(
				'type' => 'input',
				'size' => 13,
				'max' => 20,
				'eval' => 'datetime',
				'checkbox' => 0,
				'default' => 0,
				'range' => array(
					'lower' => mktime(0, 0, 0, date('m'), date('d'), date('Y'))
				),
			),
		),
		'endtime' => array(
			'exclude' => 1,
			'l10n_mode' => 'mergeIfNotBlank',
			'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.endtime',
			'config' => array(
				'type' => 'input',
				'size' => 13,
				'max' => 20,
				'eval' => 'datetime',
				'checkbox' => 0,
				'default' => 0,
				'range' => array(
					'lower' => mktime(0, 0, 0, date('m'), date('d'), date('Y'))
				),
			),
		),
		'ref_table' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:solr_universal_boost/Resources/Private/Language/locallang_db.xlf:tx_solr_universal_boost_domain_model_boostkeyword.ref_table',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim,required'
			),
		),
		'ref_uid' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:solr_universal_boost/Resources/Private/Language/locallang_db.xlf:tx_solr_universal_boost_domain_model_boostkeyword.ref_uid',
			'config' => array(
				'type' => 'input',
				'size' => 4,
				'eval' => 'int,required'
			),
		),
		'keyword' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:solr_universal_boost/Resources/Private/Language/locallang_db.xlf:tx_solr_universal_boost_domain_model_boostkeyword.keyword',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim,required'
			),
		),
	),
);

/**
 * Add special TCA field to all datatypes indexed by solr (indexqueue initialization must precede)
 */

$keywordFunc = array(
	'boostKeywords' => array (
		'exclude' => 0,
		'label' => 'Boost Keyword',
		'config' => array(
			'type' => 'user',
			'userFunc' => 'HHoechtl\SolrUniversalBoost\TCA\Keywords->keywordSelector',
		)
	)
);

/** @var \TYPO3\CMS\Core\Database\DatabaseConnection $db */
$db = $GLOBALS['TYPO3_DB'];

$itemTypes = $db->exec_SELECTgetRows('item_type', 'tx_solr_indexqueue_item', '1=1', 'item_type');

foreach($itemTypes as $itemType){
	\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns($itemType['item_type'],$keywordFunc);
	\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes($itemType['item_type'],'boostKeywords');
}


?>
<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

/**
 * BE Ajax function
 */

$TYPO3_CONF_VARS['BE']['AJAX']['tx_solr_universal_boost::getTerms'] =
	ExtensionManagementUtility::extPath('solr_universal_boost') .
	'Classes/Service/Backend/KeywordService.php:HHoechtl\SolrUniversalBoost\Service\Backend\KeywordService->ajaxGetTerms';

/*
 * XClasses
 */
$GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects']['Tx_Solr_ResultDocumentModifier_ScoreAnalyzer'] = array(
	'className' => 'HHoechtl\\SolrUniversalBoost\\Xclass\\ScoreAnalyzer_Advanced',
);

/**
 * Hooks
 */
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass'][] =
	'EXT:solr_universal_boost/Classes/Hooks/Tcemain/StoreBoostKeywords.php:HHoechtl\SolrUniversalBoost\Hooks\Tcemain\StoreBoostKeywords';

$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['solr']['IndexQueueIndexer']['preAddModifyDocuments'][] =
	'EXT:solr_universal_boost/Classes/Hooks/Solr/IndexQueue/DocumentModifier/IndexBoostKeywords.php:HHoechtl\SolrUniversalBoost\Hooks\Solr\IndexQueue\DocumentModifier\IndexBoostKeywords';
$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['solr']['Indexer']['indexPageSubstitutePageDocument'][] =
	'EXT:solr_universal_boost/Classes/Hooks/Solr/PageIndexer/IndexPageSubstituteKeywords.php:HHoechtl\SolrUniversalBoost\Hooks\Solr\PageIndexer\IndexPageSubstituteKeywords';

/**
 * eID Dispatcher
 */
$TYPO3_CONF_VARS['FE']['eID_include']['ajaxDispatcher'] = ExtensionManagementUtility::extPath('solr_universal_boost').'Classes/EidDispatcher.php';

?>
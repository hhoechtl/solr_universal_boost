<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}
use \TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

ExtensionManagementUtility::addStaticFile($_EXTKEY, 'Configuration/TypoScript', 'Apache Solr - Universal Boost');

ExtensionManagementUtility::addLLrefForTCAdescr(
	'tx_solr_universal_boost_domain_model_boostkeyword',
	'EXT:solr_universal_boost/Resources/Private/Language/locallang_csh_tx_solr_universal_boost_domain_model_boostkeyword.xlf'
);
ExtensionManagementUtility::allowTableOnStandardPages('tx_solr_universal_boost_domain_model_boostkeyword');
$TCA['tx_solr_universal_boost_domain_model_boostkeyword'] = array(
	'ctrl' => array(
		'title'	=> 'LLL:EXT:solr_universal_boost/Resources/Private/Language/locallang_db.xlf:tx_solr_universal_boost_domain_model_boostkeyword',
		'label' => 'ref_table',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		'dividers2tabs' => TRUE,

		'versioningWS' => 2,
		'versioning_followPages' => TRUE,
		'origUid' => 't3_origuid',
		'languageField' => 'sys_language_uid',
		'transOrigPointerField' => 'l10n_parent',
		'transOrigDiffSourceField' => 'l10n_diffsource',
		'delete' => 'deleted',
		'enablecolumns' => array(
			'disabled' => 'hidden',
			'starttime' => 'starttime',
			'endtime' => 'endtime',
		),
		'searchFields' => 'ref_table,ref_uid,keyword,',
		'dynamicConfigFile' => ExtensionManagementUtility::extPath($_EXTKEY) . 'Configuration/TCA/BoostKeyword.php',
		'iconfile' => ExtensionManagementUtility::extRelPath($_EXTKEY) . 'Resources/Public/Icons/tx_solr_universal_boost_domain_model_boostkeyword.png'
	),
);

?>
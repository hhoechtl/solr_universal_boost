<?php
/**
 * Copyright notice
 *
 * @author Hans Höchtl <jhoechtl@gmail.com>
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */

namespace HHoechtl\SolrUniversalBoost\Hooks\Solr\PageIndexer;


class IndexPageSubstituteKeywords implements \Tx_Solr_SubstitutePageIndexer{

	/**
	 * returns a substitute document for the currently being indexed page
	 *
	 * @param	Apache_Solr_Document	The original page document.
	 * @return	Apache_Solr_Document	returns an Apache_Solr_Document object that replace the default page document
	 */
	public function getPageDocument(\Apache_Solr_Document $originalPageDocument){
		/** @var DatabaseConnection $db */
		$db = $GLOBALS['TYPO3_DB'];

		$boostKeywords = $db->exec_SELECTgetRows(
			'keyword',
			'tx_solr_universal_boost_domain_model_boostkeyword',
			'ref_table = \'' . $originalPageDocument->getField('type')['value'] . '\' AND ref_uid = ' . $originalPageDocument->getField('uid')['value']
		);

		foreach($boostKeywords as $boostKeyword) {
			$originalPageDocument->addField('boostkeywords_stringM',$boostKeyword['keyword']);
		}

		return $originalPageDocument;
	}

} 
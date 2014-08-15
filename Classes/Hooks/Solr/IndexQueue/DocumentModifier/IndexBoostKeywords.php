<?php
/**
 * Copyright notice
 *
 * @author Hans Höchtl <jhoechtl@gmail.com>
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */

namespace HHoechtl\SolrUniversalBoost\Hooks\Solr\IndexQueue\DocumentModifier;


use TYPO3\CMS\Core\Database\DatabaseConnection;

class IndexBoostKeywords implements \Tx_Solr_IndexQueuePageIndexerDocumentsModifier{

	/**
	 * Modifies the given documents to add boostkeyword field
	 *
	 * @param	\Tx_Solr_IndexQueue_Item	The currently being indexed item.
	 * @param	integer	The language uid of the documents
	 * @param	array	An array of documents to be indexed
	 * @return	array	An array of modified documents
	 */
	public function modifyDocuments(\Tx_Solr_IndexQueue_Item $item, $language, array $documents){
		/** @var DatabaseConnection $db */
		$db = $GLOBALS['TYPO3_DB'];

		$boostKeywords = $db->exec_SELECTgetRows(
			'keyword',
			'tx_solr_universal_boost_domain_model_boostkeyword',
			'ref_table = \'' . $item->getType() . '\' AND ref_uid = ' . $item->getRecordUid()
		);

		foreach($boostKeywords as $boostKeyword) {
			foreach($documents as $k => $document){
				$documents[$k]->addField('boostkeywords_stringM', $boostKeyword['keyword']);
			}
		}

		return $documents;
	}


} 
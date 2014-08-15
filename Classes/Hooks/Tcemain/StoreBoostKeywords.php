<?php
namespace HHoechtl\SolrUniversalBoost\Hooks\Tcemain;

use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Copyright notice
 *
 * @author Hans Höchtl <jhoechtl@gmail.com>
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class StoreBoostKeywords {

	public function processDatamap_preProcessFieldArray(&$incomingFieldArray, $table, $id, \TYPO3\CMS\Core\DataHandling\DataHandler $parentObject) {

		/** @var \TYPO3\CMS\Core\Database\DatabaseConnection $db */
		$db = $GLOBALS['TYPO3_DB'];

		if (

			array_key_exists('boostKeywords', $incomingFieldArray) && is_array($incomingFieldArray['boostKeywords'])
		) {

			/*
			 * Delete removed keywords
			 */
			$valueCondition = '1=1';
			if (!empty($incomingFieldArray['boostKeywords'])) {
				$valueCondition = 'keyword NOT IN (\'' . implode("','", $incomingFieldArray['boostKeywords']) . '\')';
			}
			$db->exec_DELETEquery(
				'tx_solr_universal_boost_domain_model_boostkeyword',
				'ref_table = \'' . $table . '\' AND ' .
				'ref_uid = ' . $id . ' AND ' .
				$valueCondition
			);

			/*
			 * Find keywords currently not in database and add them
			 */
			$dbKeywords = array();
			$rows = $db->exec_SELECTgetRows(
				'keyword',
				'tx_solr_universal_boost_domain_model_boostkeyword',
				'ref_table = \'' . $table . '\' AND ' .
				'ref_uid = ' . $id
			);
			foreach ($rows as $row) {
				$dbKeywords[] = $row['keyword'];
			}

			$diff = array_diff($incomingFieldArray['boostKeywords'], $dbKeywords);

			foreach ($diff as $newKeyword) {
				$dbInsertRow = array(
					'pid' => 1,
					'ref_table' => $table,
					'ref_uid' => $id,
					'keyword' => $newKeyword
				);
				$db->exec_INSERTquery(
					'tx_solr_universal_boost_domain_model_boostkeyword',
					$dbInsertRow
				);
			}

			unset($incomingFieldArray['boostKeywords']);
		}
	}
}
<?php

namespace HHoechtl\SolrUniversalBoost\Xclass;

/**
 * Copyright notice
 *
 * @author Hans Höchtl <jhoechtl@gmail.com>
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class ScoreAnalyzer_Advanced extends \Tx_Solr_ResultDocumentModifier_ScoreAnalyzer {

	protected function analyzeScore(array $resultDocument) {
		$highScores = array();
		$debugData  = $this->search->getDebugResponse()->explain->{$resultDocument['id']};
		$highScores['fullDebug'] = $debugData;

		return $highScores;
	}

	/**
	 * Renders an overview of how the score for a certain document has been
	 * calculated.
	 *
	 * @param	array	The result document which to analyse
	 * @return	string	The HTML showing the score analysis
	 */
	protected function renderScoreAnalysis(array $highScores) {
		$content       = '';
		$content = '<table style="width: 100%; border: 1px solid #aaa; font-size: 11px; background-color: #eee;">
			<tr onclick="$(this).next().toggle()" style="cursor:pointer;"><td><strong>Full Analysis</strong> (Click to expand)</td></tr>
			<tr style="display: none;"><td><pre>' . $highScores["fullDebug"] . '</pre></td></tr>
			</table>';

		return $content;
	}

}

?>
<?php
/**
 * Copyright notice
 *
 * @author Hans Höchtl <jhoechtl@gmail.com>
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */

namespace HHoechtl\SolrUniversalBoost\Service\Backend;


use TYPO3\CMS\Core\Http\AjaxRequestHandler;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class KeywordService {

	/**
	 *
	 *
	 * @param array $params
	 * @param AjaxRequestHandler $ajaxObj
	 */
	public function ajaxGetTerms($params, &$ajaxObj) {

		// @TODO get record language
		$languageId = 0;
		$pageId = GeneralUtility::_GP('pid');
		$q = GeneralUtility::_GP('keywordAjax');

		$solrConfiguration = \Tx_Solr_Util::getSolrConfigurationFromPageId($pageId, TRUE);
		$site = \Tx_Solr_Site::getSiteByPageId($pageId);
		$solr   = GeneralUtility::makeInstance('Tx_Solr_ConnectionManager')->getConnectionByPageId(
			$pageId,
			$languageId
		);

		$suggestQuery = GeneralUtility::makeInstance('Tx_Solr_SuggestQuery', $q);
		$suggestQuery->setSiteHashFilter($site->getDomain());
		$suggestQuery->setOmitHeader();

		$search = GeneralUtility::makeInstance('Tx_Solr_Search', $solr);
		$results = json_decode($search->search($suggestQuery, 0, 0)->getRawResponse());
		$facetSuggestions = $results->facet_counts->facet_fields->{$solrConfiguration['suggest.']['suggestField']};
		$facetSuggestions = get_object_vars($facetSuggestions);

		$suggestions = '';
		foreach($facetSuggestions as $partialKeyword => $value){
			$suggestionKey = trim($suggestQuery->getKeywords() . ' ' . $partialKeyword);
			$suggestions .= '<li id="suggest-keyword-' . $suggestionKey . '"><span class="suggest-label">' . $suggestionKey . '</span></li>';
		}
		$suggestions .= '<li id="suggest-keyword-' . $q . '"><span class="suggest-label">' . $q . '</span></li>';

		$list = '<ul class="typo3-TCEforms-suggest-resultlist">' . $suggestions . '</ul>';
		$ajaxObj->addContent(0, $list);

	}

} 

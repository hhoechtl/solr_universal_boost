<?php
namespace HHoechtl\SolrUniversalBoost\TCA;

use TYPO3\CMS\Backend\Utility\IconUtility;

/**
 * Copyright notice
 *
 * @author Hans Höchtl <jhoechtl@gmail.com>
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */

class Keywords {

	/**
	 * @var \TYPO3\CMS\Core\Database\DatabaseConnection
	 */
	protected $db;

	/**
	 * @return void
	 */
	public function __construct() {
		$this->db = &$GLOBALS['TYPO3_DB'];
	}

	private function generateJS($PA){
		$currentPID = $PA['row']['pid'];
		if( $PA['table'] === 'pages' ){
			$currentPID = $PA['row']['uid'];
		}
		$deleteSpriteClasses = IconUtility::getSpriteIconClasses('actions-selection-delete');
		$script = "
		 Ext.onReady(function() {

		 	var addToList = function(item) {
				var idParts = item.id.split('-');
				var keywordList = Ext.get('current-keywords');
				var lineToInsert = '<div style=\"display:inline\">' + idParts[2] + ' <span class=\"delete-boostkeyword " . $deleteSpriteClasses . "\" style=\"margin-right: 20px; cursor:pointer;\">&nbsp;</span>';
				lineToInsert += '<input type=\"hidden\" name=\"" . $PA['itemFormElName'] . "[]\" value=\"' + idParts[2] + '\" /></div>';
				keywordList.createChild(lineToInsert);
				$(suggestField).value = '';
		 	}

		 	var defaultValue = '" . $GLOBALS['LANG']->sL('LLL:EXT:lang/locallang_core.xlf:labels.findRecord') . "';

			PATH_typo3 = '/typo3/';
			objectId = 'boostkeyword';
			suggestField = objectId + 'Suggest';
			suggestResultList = objectId + 'SuggestChoices';
			var autocompleter = new Ajax.Autocompleter(suggestField, suggestResultList, PATH_typo3 + 'ajax.php', {
				minChars: 2,
				updateElement: addToList,
				parameters: 'ajaxID=tx_solr_universal_boost::getTerms&pid=" . $currentPID . "' ,
				indicator: objectId + 'SuggestIndicator'
			}
			);

			var checkDefaultValue = function() {
				if ($(suggestField).value == defaultValue) {
					$(suggestField).value = '';
				}
			}

			$(suggestField).observe('focus', checkDefaultValue);
			$(suggestField).observe('keydown', checkDefaultValue);

			Ext.getBody().on(
				'click',
				function(event, target) {
					Ext.get(target).parent().remove();
				},
				null,
				{
					delegate: 'span.delete-boostkeyword'
				}
			);
		 });
		";
		$GLOBALS['SOBE']->doc->getPageRenderer()->addJsInlineCode("autocompleteTerms", $script);
	}

	/**
	 * Collects the keywords for the current record from the database
	 *
	 * @param string $table
	 * @param integer $uid
	 *
	 * @return array|NULL
	 */
	private function getKeywords($table, $uid){
		$rows = $this->db->exec_SELECTgetRows(
			'uid,keyword',
			'tx_solr_universal_boost_domain_model_boostkeyword',
			'ref_uid = ' . intval($uid) . ' AND ref_table = ' . $this->db->fullQuoteStr($table, '')
		);
		return $rows;
	}

	/**
	 * Generates the list of keywords for the current record as collection of keywords and delete icons
	 *
	 * @param $keywords
	 *
	 * @return string
	 */
	private function generateKeywordList($keywords, $PA){
		$html = '<div id="current-keywords" style="padding-top: 10px;">';
		$deleteSpriteClasses = IconUtility::getSpriteIconClasses('actions-selection-delete');
		foreach($keywords as $keyword){
			$html .= '<div style="display:inline">';
			$html .= $keyword['keyword'] . ' <span class="delete-boostkeyword ' . $deleteSpriteClasses . '" style="margin-right: 20px; cursor:pointer;">&nbsp;</span>';
			$html .= '<input type="hidden" name="' . $PA['itemFormElName'] . '[]" value="' . $keyword['keyword'] . '"/>';
			$html .= '</div>';
		}
		$html .= '</div>';
		return $html;
	}



	public function keywordSelector($PA, $pObj) {
		$GLOBALS['SOBE']->doc->getPageRenderer()->loadScriptaculous();
		$this->generateJS($PA);

		$table = $PA['table'];
		$uid = $PA['row']['uid'];

		$html = '';
		$html .= '
		<div class="typo3-TCEforms-suggest typo3-TCEforms-suggest-position-right" id="boostkeyword">
			<input name="keywordAjax" type="text" id="boostkeywordSuggest" value="' . $GLOBALS['LANG']->sL('LLL:EXT:lang/locallang_core.xlf:labels.findRecord') . '" class="typo3-TCEforms-suggest-search" />
			<div class="typo3-TCEforms-suggest-indicator" style="display: none;" id="boostkeywordSuggestIndicator">
				<img src="' . $GLOBALS['BACK_PATH'] . 'gfx/spinner.gif" alt="' . $GLOBALS['LANG']->sL('LLL:EXT:lang/locallang_core.xlf:alttext.suggestSearching') . '" />
			</div>
			<div class="typo3-TCEforms-suggest-choices" style="display: none;" id="boostkeywordSuggestChoices"></div>

		</div>';

		$existingKeywords = $this->getKeywords($table,$uid);
		$html .= $this->generateKeywordList($existingKeywords, $PA);

		return $html;
	}
}
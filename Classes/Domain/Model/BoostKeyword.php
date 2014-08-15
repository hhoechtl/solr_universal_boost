<?php
namespace HHoechtl\SolrUniversalBoost\Domain\Model;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2013 Hans Höchtl <jhoechtl@gmail.com>
 *  
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 *
 *
 * @package solr_universal_boost
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
class BoostKeyword extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity {

	/**
	 * refTable
	 *
	 * @var \string
	 * @validate NotEmpty
	 */
	protected $refTable;

	/**
	 * refUid
	 *
	 * @var \integer
	 * @validate NotEmpty
	 */
	protected $refUid;

	/**
	 * keyword
	 *
	 * @var \string
	 * @validate NotEmpty
	 */
	protected $keyword;

	/**
	 * Returns the refTable
	 *
	 * @return \string $refTable
	 */
	public function getRefTable() {
		return $this->refTable;
	}

	/**
	 * Sets the refTable
	 *
	 * @param \string $refTable
	 * @return void
	 */
	public function setRefTable($refTable) {
		$this->refTable = $refTable;
	}

	/**
	 * Returns the refUid
	 *
	 * @return \integer $refUid
	 */
	public function getRefUid() {
		return $this->refUid;
	}

	/**
	 * Sets the refUid
	 *
	 * @param \integer $refUid
	 * @return void
	 */
	public function setRefUid($refUid) {
		$this->refUid = $refUid;
	}

	/**
	 * Returns the keyword
	 *
	 * @return \string $keyword
	 */
	public function getKeyword() {
		return $this->keyword;
	}

	/**
	 * Sets the keyword
	 *
	 * @param \string $keyword
	 * @return void
	 */
	public function setKeyword($keyword) {
		$this->keyword = $keyword;
	}

}
?>
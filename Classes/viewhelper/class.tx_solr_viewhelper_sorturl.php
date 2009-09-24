<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2009 Ingo Renner <ingo@typo3.org>
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
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
 * Creates a solr sorting URL by expanding a ###SOLR_URL:sort_field### marker.
 *
 * @author	Ingo Renner <ingo@typo3.org>
 * @package TYPO3
 * @subpackage solr
 */
class tx_solr_viewhelper_SortUrl implements tx_solr_ViewHelper {

	/**
	 * Holds th solr configuration
	 *
	 * @var array
	 */
	protected $configuration = array();

	/**
	 * An instance of a Solr Search
	 *
	 * @var tx_solr_Search
	 */
	protected $search;

	/**
	 * An instance of the current query, holding all parameters.
	 *
	 * @var tx_solr_Query
	 */
	protected $query = null;

	/**
	 * constructor for class tx_solr_viewhelper_SortUrl
	 */
	public function __construct(array $arguments = array()) {
		$this->search = t3lib_div::makeInstance('tx_solr_Search');

		$this->configuration = tx_solr_Util::getSolrConfiguration();
		$this->query = $this->search->getQuery();
	}

	/**
	 * Returns an URL that switches sorting to the given sorting field
	 *
	 * @param array $arguments
	 * @return	string
	 */
	public function execute(array $arguments = array()) {
		$sortUrl = '';
		$configuredSortingFields = $this->configuration['search.']['sorting.']['fields.'];
		$sortField = $arguments[0];

		$urlParameters = t3lib_div::_GP('tx_solr');
		$urlSortingParameter = $urlParameters['sort'];
		list($currentSortByField, $currentSortDirection) = explode(' ', $urlSortingParameter);

		if (array_key_exists($sortField, $configuredSortingFields) && $configuredSortingFields[$sortField] == 1) {
			$sortDirection = $this->configuration['search.']['sorting.']['defaultOrder'];
			$sortParameter = $sortField . ' ' . $sortDirection;

				// toggle sorting direction for the current sorting field
			if ($currentSortByField == $sortField) {
				switch ($currentSortDirection) {
					case 'asc':
						$sortDirection = 'desc';
						break;
					case 'desc':
						$sortDirection = 'asc';
						break;
				}

				$sortParameter = $sortField . ' ' . $sortDirection;
			}

			$sortUrl = $this->query->getQueryUrl(array('sort' => $sortParameter));
		}

		return $sortUrl;
	}
}


if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/solr/classes/viewhelper/class.tx_solr_viewhelper_sorturl.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/solr/classes/viewhelper/class.tx_solr_viewhelper_sorturl.php']);
}

?>
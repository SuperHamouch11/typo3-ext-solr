<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2009 Ingo Renner <ingo.renner@dkd.de>
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
 * Utility class for tx_solr
 *
 * @author	Ingo Renner <ingo.renner@dkd.de>
 * @package TYPO3
 * @subpackage solr
 */
class Tx_Solr_Util {

	/**
	 * Generates a site specific key using the site url, encryption key, and
	 * the extension key sent through md5.
	 *
	 * @return	string	a site specific hash
	 */
	public static function getSiteHash() {
		return md5(
			t3lib_div::getIndpEnv('TYPO3_SITE_URL') .
			$GLOBALS['TYPO3_CONF_VARS']['SYS']['encryptionKey'] .
			'tx_solr'
		);
	}

	/**
	 * Generates a document id for documents representing page records.
	 *
	 * @param	integer	$uid The page's uid
	 * @param	integer The page's typeNum
	 * @param	integer	$language the language id, defaults to 0
	 * @param	string	$accessGroups comma separated list of uids of groups that have access to that page
	 * @return	string	the document id for that page
	 */
	public static function getPageDocumentId($uid, $typeNum = 0, $language = 0, $accessGroups = '0,-1') {
		return self::getSiteHash() . '/pages/' . $uid . '/' . $typeNum . '/' . $language . '/' . $accessGroups;
	}

	/**
	 * Generates a document id in the form $siteHash/$type/$uid.
	 *
	 * @param	string	the records table name
	 * @param	integer	the record's pid
	 * @param	integer	the record's uid
	 * @param	string	optional record type, can also be used to represent a single view page id
	 * @return	string	a document id
	 */
	public static function getDocumentId($table, $pid, $uid, $type = '') {
		$id = self::getSiteHash() . '/' . $table . '/' . $pid . '/' . $uid;

		if (!empty($type)) {
			$id .= '/' . $type;
		}

		return $id;
	}

	/**
	 * Converts a date from unix timestamp to ISO 8601 format.
	 *
	 * @param	integer	unix timestamp
	 * @return	string	the date in ISO 8601 format
	 */
	public static function timestampToIso($timestamp) {
		return date('Y-m-d\TH:i:s\Z', $timestamp);
	}

	/**
	 * Returns given word as CamelCased.
	 *
	 * Converts a word like "send_email" to "SendEmail". It
	 * will remove non alphanumeric characters from the word, so
	 * "who's online" will be converted to "WhoSOnline"
	 *
	 * @param	string	Word to convert to camel case
	 * @return	string	UpperCamelCasedWord
	 */
	public static function camelize($word) {
		return str_replace(' ', '', ucwords(preg_replace('![^A-Z^a-z^0-9]+!', ' ', $word)));
	}

	/**
	 * Returns a given CamelCasedString as an lowercase string with underscores.
	 * Example: Converts BlogExample to blog_example, and minimalValue to minimal_value
	 *
	 * @param	string		$string: String to be converted to lowercase underscore
	 * @return	string		lowercase_and_underscored_string
	 */
	public static function camelCaseToLowerCaseUnderscored($string) {
		return strtolower(preg_replace('/(?<=\w)([A-Z])/', '_\\1', $string));
	}

	/**
	 * Returns a given string with underscores as UpperCamelCase.
	 * Example: Converts blog_example to BlogExample
	 *
	 * @param	string		$string: String to be converted to camel case
	 * @return	string		UpperCamelCasedWord
	 */
	public static function underscoredToUpperCamelCase($string) {
		return str_replace(' ', '', ucwords(str_replace('_', ' ', strtolower($string))));
	}

	/**
	 * Shortcut to retrieve the configuration for EXT:solr from TSFE
	 *
	 * @return array	Solr configuration
	 */
	public static function getSolrConfiguration() {
			// TODO if in BE, create a fake TSFE and retrieve the configuration
			// TODO merge flexform configuration
		return $GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_solr.']['settings.'];
	}
}


if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/solr/classes/class.tx_solr_util.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/solr/classes/class.tx_solr_util.php']);
}

?>
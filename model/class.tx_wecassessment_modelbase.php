<?php

/***************************************************************
* Copyright notice
*
* (c) 2007 Foundation for Evangelism (info@evangelize.org)
* All rights reserved
*
* This file is part of the Web-Empowered Church (WEC)
* (http://webempoweredchurch.org) ministry of the Foundation for Evangelism
* (http://evangelize.org). The WEC is developing TYPO3-based
* (http://typo3.org) free software for churches around the world. Our desire
* is to use the Internet to help offer new life through Jesus Christ. Please
* see http://WebEmpoweredChurch.org/Jesus.
*
* You can redistribute this file and/or modify it under the terms of the
* GNU General Public License as published by the Free Software Foundation;
* either version 2 of the License, or (at your option) any later version.
*
* The GNU General Public License can be found at
* http://www.gnu.org/copyleft/gpl.html.
*
* This file is distributed in the hope that it will be useful for ministry,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
* GNU General Public License for more details.
*
* This copyright notice MUST APPEAR in all copies of the file!
***************************************************************/

/**
 * General purpose class that the specific data models implement.
 *
 * @author	Web-Empowered Church Team <assessment@webempoweredchurch.org>
 * @package TYPO3
 * @subpackage tx_wecassessment
 */
class tx_wecassessment_modelbase {
	
	/**
	 * Gets the current table name.  If one is not defined, a guess is made
	 * based on the class name.
	 *
	 * @return		string
	 */
	function getTableName() {
		if(!$this->_tableName) {
			$this->_tableName = get_class($this);
		}
		
		return $this->_tableName;
	}

	/**
	 * Generates a WHERE clause for the current table.
	 * @param		string		The table to generate the WHERE clause for.
	 * @param		string		Additional WHERE items.
	 * @param		boolean		True if hidden records should be included.
	 */
	function getWhere($table, $where, $showHidden=false) {
		$enableFields = tx_wecassessment_modelbase::getEnableFields($table, $showHidden);

		if($enableFields and $where) {
			$returnWhere = $enableFields." AND ".$where;
		} else {
			$returnWhere = $enableFields.$where;
		}
		
		return $returnWhere;
	}
	
	/**
	 * Combines two WHERE clauses with the given separator.
	 * @param		string		The first WHERE clause.
	 * @param		string		The second WHERE clause.
	 * @param		string		Optional separator.  By default AND is used.
	 */
	function combineWhere($where1, $where2, $separator='AND') {
		if ($where1 and $where2) {
			$where = $where1.' '.$separator.' '.$where2;
		} else {
			$where = $where1.$where2;
		}
		
		return $where;
	}
		
	
	/**
	 * Performs a workspace overlay when working with records in the backend.
	 * @param		string		Name of the table.
	 * @param		array		Table row.
	 * @return		array		Processed row.
	 */
	function processRow($table, $row) {
		if(TYPO3_MODE == 'BE') {
			t3lib_beFunc::workspaceOL($table,$row);
		}
		
		return $row;
	}
	
	/**
	 * Gets the enableFields for the specified table, in both the frontend and backend.
	 * @param		string		Name of the table.
	 * @param		boolen		If true, hidden records are included.
	 * @return		string		WHERE clause for the enableFields.
	 */
	function getEnableFields($table, $showHidden=false) {
		if(TYPO3_MODE == 'FE') {
			$enableFields = $GLOBALS['TSFE']->sys_page->enableFields($table,$showHidden ? $showHidden : ($table=='pages' ? $GLOBALS['TSFE']->showHiddenPage : $GLOBALS['TSFE']->showHiddenRecords));
		} else {
			$enableFields = $showHidden ? '' : t3lib_BEfunc::BEenableFields($table).t3lib_BEfunc::deleteClause($table);
		}
		
		/* Trim off the opening "AND " */
		$enableFields = substr($enableFields, 5, strlen($enableFields));
		
		return $enableFields;
	}
	
	
	/**
	 * Fetches a row from the database.
	 * @param		string		Name of the table.
	 * @param		integer		UID to fetch.
	 * @param		string		WHERE clause
	 * @param		boolean		If true, hidden records are included.
	 * @return		array		Associate array for the row.
	 */
	function getRow($table, $uid, $where='', $showHidden=false) {
		if($where) {
			$where = tx_wecassessment_modelbase::getWhere($table, 'uid='.$uid.' AND '.$where, $showHidden);
		} else {
			$where = tx_wecassessment_modelbase::getWhere($table, 'uid='.$uid, $showHidden);
		}
		$result = $GLOBALS['TYPO3_DB']->exec_SELECTquery('*', $table, $where);
		
		$row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($result);
		$row = &tx_wecassessment_modelbase::processRow($table, $row);
		$GLOBALS['TYPO3_DB']->sql_free_result($result);
		
		return $row;		
	}
	
	
	/**
	 * Gets the label for the current record.  This default method uses TYPO3
	 * Core functions to get the label and is commonly overridden.
	 *
	 * @return		string
	 */
	function getLabel() {
		if(TYPO3_MODE == 'BE') {
			$label = t3lib_befunc::getRecordTitle($this->getTableName(), $this->toArray());
		} else {
			/* @todo 	What do we do in frontend mode? */
			die("Not in backend mode.");
		}
		
		return $label;
	}
	
	

}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/wec_assessment/model/class.tx_wecassessment_modelbase.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/wec_assessment/model/class.tx_wecassessment_modelbase.php']);
}
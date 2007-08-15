<?php
/***************************************************************
* Copyright notice
*
* (c) 2007 Foundation For Evangelism (info@evangelize.org)
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
 * < Insert class description here. />
 * 
 * @author Web-Empowered Church Team <developer@webempoweredchurch.org>
 * @package TYPO3
 * @subpackage tx_wecext
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

	function getWhere($table, $where, $showHidden=false) {
		$enableFields = tx_wecassessment_modelbase::getEnableFields($table, $showHidden);

		if($enableFields and $where) {
			$returnWhere = $enableFields." AND ".$where;
		} else {
			$returnWhere = $enableFields.$where;
		}
		
		return $returnWhere;
	}
	
	function combineWhere($where1, $where2, $separator='AND') {
		if ($where1 and $where2) {
			$where = $where1.' '.$separator.' '.$where2;
		} else {
			$where = $where1.$where2;
		}
		
		return $where;
	}
		
	
	function processRow($table, $row) {
		t3lib_beFunc::workspaceOL($table,$row);
		return $row;
	}
	
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

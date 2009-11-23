<?php
/*
 * Statistics plugin for Wolf CMS
 * November 2009
 * @author Ian Dundas for Band-x.org
 */
class StatisticsController extends PluginController {

	public function __construct() {
		$this->statistics_manager = new StatisticsManager();

		if (defined('CMS_BACKEND')) {
			$this->setLayout('backend');
		} else {
			$this->setLayout('plaintext');
		}
	}
	public function documentation() {
		$this->display(STATISTICS_VIEWS_BASE.'/backend/documentation/index');
    }

}
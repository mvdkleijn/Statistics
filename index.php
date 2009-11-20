<?php
/*
 * API plugin for Wolf CMS
 * November 2009
 * @author Ian Dundas for Band-x.org
 */
define('STATISTICS_VIEWS_BASE', 'statistics/views');
Plugin::setInfos(array(
	'id'		=> 'statistics',
	'title'		=> 'Statistics',
	'description'   => 'Ian\'s wicked Statistics, dev1',
	'version'       => '0.1',
    'type'		=>	'both'
));

Plugin::addController('statistics', 'Statistics', 'administrator,developer', TRUE);

if (defined('CMS_BACKEND')) {
	#load different controller here? couldn't work out how to do this.
	Dispatcher::addRoute(array(


	));
} else {
	
	Dispatcher::addRoute(array(

	));

}
include('models/StatisticsManager.php');
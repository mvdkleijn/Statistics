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

function capture_event()
{
	$sm = new StatisticsManager();
	$sm->storeRequestData($_SERVER,$_POST,$_GET);
}

#expected to be an event in wolf 0.8
Observer::observe('dispatch_route_found', 'capture_event');
#disable the following once <code>Observer::notify('dispatch_route_found', $uri);</code is upstream
//@deprecated
Observer::observe('page_requested', 'capture_event');


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
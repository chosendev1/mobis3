<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// shared_options : highcharts global settings, like interface or language
$config['shared_options'] = array(
	'chart' => array(
		'backgroundColor' => array(
			'linearGradient' => array(0, 0, 500, 500),
			'stops' => array(
				array(0, 'rgb(255, 255, 255)'),
				array(1, 'rgb(240, 240, 255)')
			)
		),
		'shadow' => true,
		
	  'exporting' => array(
     	'enabled' => true
     ),
	)
);



// Template Example
$config['chart_template'] = array(
	'chart' => array(
		'renderTo' => 'graph',
		'defaultSeriesType' => 'column',
		'backgroundColor' => array(
			'linearGradient' => array(0, 500, 0, 0),
			'stops' => array(
				array(0, 'rgb(255, 255, 255)'),
				array(1, 'rgb(190, 200, 255)')
			)
		),
     ),
     'colors' => array(
     	 '#ED561B', '#50B432'
     ),
     'credits' => array(
     	'enabled'=> false,
     	'text'	=> 'highcharts library on GitHub',
		'href' => 'https://github.com/ronan-gloo/codeigniter-highcharts-library'
     ),
     'title' => array(
		'text' => '`'
     ),
     'legend' => array(
     	'enabled' => false
     ),
    'yAxis' => array(
		'title' => array(
			'text' => 'Amount (ugx)'
		)
	),
	'xAxis' => array(
		'title' => array(
			'text' => 'Months of the Year'
		)
	),
	'tooltip' => array(
		'shared' => true
	)
);








// Template Example
$config['chart_template1'] = array(
	'chart' => array(
		'renderTo' => 'graph',
		'defaultSeriesType' => 'column',
		'backgroundColor' => array(
			'linearGradient' => array(0, 500, 0, 0),
			'stops' => array(
				array(0, 'rgb(255, 255, 255)'),
				array(1, 'rgb(190, 200, 255)')
			)
		),
     ),
     'colors' => array(
     	 '#ED561B', '#50B432'
     ),
     'credits' => array(
     	'enabled'=> false,
     	'text'	=> 'highcharts library on GitHub',
		'href' => 'https://github.com/ronan-gloo/codeigniter-highcharts-library'
     ),
     'title' => array(
		'text' => '`'
     ),
     'legend' => array(
     	'enabled' => false
     ),
    'yAxis' => array(
		'title' => array(
			'text' => 'Amount (ugx)'
		)
	),
	'xAxis' => array(
		'title' => array(
			'text' => 'Days of the week'
		)
	),
	'tooltip' => array(
		'shared' => true
	)
);
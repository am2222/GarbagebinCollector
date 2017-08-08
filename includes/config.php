<?php

$cfg['db_server'] = 'localhost';
$cfg['db_username'] = 'postgres';
$cfg['db_password'] = '2610557';
$cfg['db_database'] = 'masiryabi';
$cfg['db_prefix'] = 'pt_';

$cfg['site_name'] = 'Parallel Track';
$cfg['exceptions_logpath'] = $includes_path . 'other/exceptions.txt';

$cfg['devmode'] = true;

//Postcode Anywhere
$cfg['postcodeanywhere']['account_code'] = '';
$cfg['postcodeanywhere']['license_code'] = '';

$cfg['site_url'] = 'http://127.0.0.1:8280/car/';

$cfg['googlemap_api_key'] = '';

$cfg['map_default_lat'] = 51.446649951597;
$cfg['map_default_lon'] = 0.056037452408098;
$cfg['map_default_zoom'] = 7;

$cfg['dispupd_interv_sec'] = 30;

$cfg['email_contact'] = 'demo@paralleltrack.co.uk';
$cfg['email_system_from'] = 'demo@paralleltrack.co.uk';

$cfg['pda_pages'] = array('pda', 'pda_map');

$cfg['pda_map_size'] = array('width' => 200, 'height' => 200);

$cfg['demo_username'] = 'demo@paralleltrack.co.uk';

$cfg['timezone_default'] = 'Europe/London';

$cfg['position_report_limit'] = 12000;

$cfg['linecol'] = array(
	0 => array(//blue
		'html' => '#8080ff',
		'html_map' => '#0000ff',
		'kml' => '7fff0000',
	),
	1 => array(//red
		'html' => '#ff8080',
		'html_map' => '#ff0000',
		'kml' => '7f0000ff',
	),
);

$cfg['icon'] = array(
	0 => array(
		'name' => 'Red Car',
		'img' => array(
			'config' => 'resources/car_icons/png/48x48/car_red.png',
			'map' => 'resources/car_icons/png/32x32/car_red.png',
			'map_select' => 'resources/car_icons/png/24x24/car_red.png',
		),
	),
	1 => array(
		'name' => 'Black Car',
		'img' => array(
			'config' => 'resources/car_icons/png/48x48/car_black.png',
			'map' => 'resources/car_icons/png/32x32/car_black.png',
			'map_select' => 'resources/car_icons/png/24x24/car_black.png',
		),
	),
	2 => array(
		'name' => 'Silver Car',
		'img' => array(
			'config' => 'resources/car_icons/png/48x48/car_silver.png',
			'map' => 'resources/car_icons/png/32x32/car_silver.png',
			'map_select' => 'resources/car_icons/png/24x24/car_silver.png',
		),
	),
	3 => array(
		'name' => 'Cabriolet Car',
		'img' => array(
			'config' => 'resources/car_icons/png/48x48/car_cabriolet.png',
			'map' => 'resources/car_icons/png/32x32/car_cabriolet.png',
			'map_select' => 'resources/car_icons/png/24x24/car_cabriolet.png',
		),
	),
	4 => array(
		'name' => 'Blue Car',
		'img' => array(
			'config' => 'resources/car_icons/png/48x48/car_blue.png',
			'map' => 'resources/car_icons/png/32x32/car_blue.png',
			'map_select' => 'resources/car_icons/png/24x24/car_blue.png',
		),
	),
	5 => array(
		'name' => 'Police Car',
		'img' => array(
			'config' => 'resources/car_icons/png/48x48/car_police.png',
			'map' => 'resources/car_icons/png/32x32/car_police.png',
			'map_select' => 'resources/car_icons/png/24x24/car_police.png',
		),
	),
	6 => array(
		'name' => 'Race Car',
		'img' => array(
			'config' => 'resources/car_icons/png/48x48/car_race.png',
			'map' => 'resources/car_icons/png/32x32/car_race.png',
			'map_select' => 'resources/car_icons/png/24x24/car_race.png',
		),
	),
	7 => array(
		'name' => 'Car (Damaged)',
		'img' => array(
			'config' => 'resources/car_icons/png/48x48/car_damage.png',
			'map' => 'resources/car_icons/png/32x32/car_damage.png',
			'map_select' => 'resources/car_icons/png/24x24/car_damage.png',
		),
	),
	8 => array(
		'name' => 'Taxi',
		'img' => array(
			'config' => 'resources/car_icons/png/48x48/taxi.png',
			'map' => 'resources/car_icons/png/32x32/taxi.png',
			'map_select' => 'resources/car_icons/png/24x24/taxi.png',
		),
	),
	9 => array(
		'name' => 'Jeep',
		'img' => array(
			'config' => 'resources/car_icons/png/48x48/jeep.png',
			'map' => 'resources/car_icons/png/32x32/jeep.png',
			'map_select' => 'resources/car_icons/png/24x24/jeep.png',
		),
	),
	10 => array(
		'name' => 'Hummer',
		'img' => array(
			'config' => 'resources/car_icons/png/48x48/hummer.png',
			'map' => 'resources/car_icons/png/32x32/hummer.png',
			'map_select' => 'resources/car_icons/png/24x24/hummer.png',
		),
	),
	11 => array(
		'name' => 'Pick-up',
		'img' => array(
			'config' => 'resources/car_icons/png/48x48/pick-up.png',
			'map' => 'resources/car_icons/png/32x32/pick-up.png',
			'map_select' => 'resources/car_icons/png/24x24/pick-up.png',
		),
	),
	12 => array(
		'name' => 'Pick-up (Laden)',
		'img' => array(
			'config' => 'resources/car_icons/png/48x48/pick-up_laden.png',
			'map' => 'resources/car_icons/png/32x32/pick-up_laden.png',
			'map_select' => 'resources/car_icons/png/24x24/pick-up_laden.png',
		),
	),
	13 => array(
		'name' => 'Van',
		'img' => array(
			'config' => 'resources/car_icons/png/48x48/van.png',
			'map' => 'resources/car_icons/png/32x32/van.png',
			'map_select' => 'resources/car_icons/png/24x24/van.png',
		),
	),
	14 => array(
		'name' => 'Taxi Lorry',
		'img' => array(
			'config' => 'resources/car_icons/png/48x48/taxi-lorry.png',
			'map' => 'resources/car_icons/png/32x32/taxi-lorry.png',
			'map_select' => 'resources/car_icons/png/24x24/taxi-lorry.png',
		),
	),
	15 => array(
		'name' => 'Panel Truck',
		'img' => array(
			'config' => 'resources/car_icons/png/48x48/panel_truck.png',
			'map' => 'resources/car_icons/png/32x32/panel_truck.png',
			'map_select' => 'resources/car_icons/png/24x24/panel_truck.png',
		),
	),
	16 => array(
		'name' => 'Lorry',
		'img' => array(
			'config' => 'resources/car_icons/png/48x48/lorry.png',
			'map' => 'resources/car_icons/png/32x32/lorry.png',
			'map_select' => 'resources/car_icons/png/24x24/lorry.png',
		),
	),
	17 => array(
		'name' => 'Trailer Lorry',
		'img' => array(
			'config' => 'resources/car_icons/png/48x48/trailer-lorry.png',
			'map' => 'resources/car_icons/png/32x32/trailer-lorry.png',
			'map_select' => 'resources/car_icons/png/24x24/trailer-lorry.png',
		),
	),
	18 => array(
		'name' => 'Tanker Truck',
		'img' => array(
			'config' => 'resources/car_icons/png/48x48/tank-truck.png',
			'map' => 'resources/car_icons/png/32x32/tank-truck.png',
			'map_select' => 'resources/car_icons/png/24x24/tank-truck.png',
		),
	),
	19 => array(
		'name' => 'Ambulance',
		'img' => array(
			'config' => 'resources/car_icons/png/48x48/ambulance.png',
			'map' => 'resources/car_icons/png/32x32/ambulance.png',
			'map_select' => 'resources/car_icons/png/24x24/ambulance.png',
		),
	),
	20 => array(
		'name' => 'Fire Engine',
		'img' => array(
			'config' => 'resources/car_icons/png/48x48/fire-engine.png',
			'map' => 'resources/car_icons/png/32x32/fire-engine.png',
			'map_select' => 'resources/car_icons/png/24x24/fire-engine.png',
		),
	),
	21 => array(
		'name' => 'Red Bus',
		'img' => array(
			'config' => 'resources/car_icons/png/48x48/bus_red.png',
			'map' => 'resources/car_icons/png/32x32/bus_red.png',
			'map_select' => 'resources/car_icons/png/24x24/bus_red.png',
		),
	),
	22 => array(
		'name' => 'Blue Bus',
		'img' => array(
			'config' => 'resources/car_icons/png/48x48/bus_blue.png',
			'map' => 'resources/car_icons/png/32x32/bus_blue.png',
			'map_select' => 'resources/car_icons/png/24x24/bus_blue.png',
		),
	),
	23 => array(
		'name' => 'Trolley Bus',
		'img' => array(
			'config' => 'resources/car_icons/png/48x48/trolley_bus.png',
			'map' => 'resources/car_icons/png/32x32/trolley_bus.png',
			'map_select' => 'resources/car_icons/png/24x24/trolley_bus.png',
		),
	),
);

//Zoom level disances between markers (miles)
$cfg['zoomlevel_markdist'] = array(
	0 => 900,
	1 => 700,
	2 => 500,
	3 => 300,
	4 => 150,
	5 => 80,
	6 => 50,
	7 => 30,
	8 => 14,
	9 => 9,
	10 => 5,
	11 => 3,
	12 => 1,
	13 => 0.6,
	14 => 0.4,
	15 => 0.15,
	16 => 0.1,
	17 => 0.03,
	18 => 0.02,
	19 => 0.015,
	20 => 0.015,
	21 => 0.015,
);

$cfg['fix_types'] = array(
	0 => 'Invalid Fix',
	2 => '2D Fix',
	3 => '3D Fix',
);

$cfg['auth_cookie_expiry'] = 60 * 60 * 24 * 365;

$cfg['includes_path_absolute'] = '';

$cfg['rawdatarec_log_path'] = $includes_path . 'other/rawdatareclog.txt';

$cfg['rawdatalice_log_path'] = $includes_path . 'other/rawdatalicelog.txt';

$cfg['pca_map_cache_path'] = $includes_path . 'other/pca_map_cache/';

$tbl['config'] = $cfg['db_prefix'] . 'config';
$tbl['user'] = $cfg['db_prefix'] . 'user';
$tbl['position'] = $cfg['db_prefix'] . 'position';
$tbl['unit'] = $cfg['db_prefix'] . 'unit';

$cfg['btn_template_path'] = $includes_path . 'other/btn_template/';
//$cfg['btn_cache_path'] = $publichtml_path . 'resources/btn_cache/';
$cfg['btn_font_path'] = $cfg['btn_template_path'] . 'tahomabd.ttf';

$cfg['user_group'] = array(
	1 => array(
		'name' => 'Main Admin',
	),
	2 => array(
		'name' => 'Admin',
	),
	3 => array(
		'name' => 'Demo User',
	),
	4 => array(
		'name' => 'User',
	),
);

//date_default_timezone_set('Europe/London');
//date_default_timezone_set('America/Bahia');
date_default_timezone_set('UTC');

?>
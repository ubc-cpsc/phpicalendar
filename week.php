<?php
$current_view = "week";
define('BASE', './');
require_once(BASE.'functions/ical_parser.php');
require_once(BASE.'functions/list_functions.php');
require_once(BASE.'functions/template.php');
header("Content-Type: text/html; charset=$phpiCal_config->charset");

$minical_view = $current_view;
switch ($phpiCal_config->minical_view) {
	case 'day':
	case 'week':
	case 'month':
		$minical_view = $phpiCal_config->minical_view;
		break;
}

$starttime 			= "0500";
$weekstart 			= 1;
$unix_time 			= strtotime($getdate);
$today_today        = date('Ymd', time() + $phpiCal_config->second_offset); 
$next_week 			= date("Ymd", strtotime("+1 week",  $unix_time));
$prev_week 			= date("Ymd", strtotime("-1 week",  $unix_time));
$next_day			= date('Ymd', strtotime("+1 day",  $unix_time));
$prev_day 			= date('Ymd', strtotime("-1 day",  $unix_time));
$start_week_time 	= strtotime(dateOfWeek($getdate, $phpiCal_config->week_start_day));
$end_week_time 		= $start_week_time + (($phpiCal_config->week_length - 1) * 25 * 60 * 60);
$start_week 		= localizeDate($dateFormat_week, $start_week_time);
$end_week 			= localizeDate($dateFormat_week, $end_week_time);
$display_date 		= "$start_week - $end_week";
$sidebar_date 		= localizeDate($dateFormat_week_list, $unix_time);

// For the side months
preg_match ("/([0-9]{4})([0-9]{2})([0-9]{2})/", $getdate, $day_array2);
$this_day 	= $day_array2[3]; 
$this_month = $day_array2[2];
$this_year 	= $day_array2[1];

// select for calendars
$available		= availableCalendars($username, $password, $phpiCal_config->ALL_CALENDARS_COMBINED);
$list_icals 	= display_ical_list($available);
$list_years 	= list_years();
$list_months 	= list_months();
$list_weeks 	= list_weeks();
$list_jumps 	= list_jumps();
$list_calcolors = list_calcolors();
$list_icals_pick = display_ical_list($available, TRUE);

// login/logout
$is_logged_in = ($username != '' && !$invalid_login) ? true : false;
$show_user_login = (!$is_logged_in && $phpiCal_config->allow_login == 'yes');
$login_querys = login_querys();
$logout_querys = logout_querys();

$page = new Page(BASE.'templates/'.$phpiCal_config->template.'/week.tpl');

$page->replace_files(array(
	'header'			=> BASE.'templates/'.$phpiCal_config->template.'/header.tpl',
	'event_js'			=> BASE.'functions/event.js',
	'footer'			=> BASE.'templates/'.$phpiCal_config->template.'/footer.tpl',
    'sidebar'           => BASE.'templates/'.$phpiCal_config->template.'/sidebar.tpl',
    'search_box'        => BASE.'templates/'.$phpiCal_config->template.'/search_box.tpl'
	));

$page->replace_tags(array(
	'version'			=> $phpiCal_config->phpicalendar_version,
	'charset'			=> $phpiCal_config->charset,
	'default_path'		=> $phpiCal_config->default_path,
	'template'			=> $phpiCal_config->template,
	'cal'				=> $cal,
	'getdate'			=> $getdate,
 	'getcpath'			=> "&cpath=$cpath",
    'cpath'             => $cpath,
	'calendar_name'		=> $cal_displayname,
	'display_date'		=> $display_date,
	'current_view'		=> $current_view,
	'sidebar_date'		=> $sidebar_date,
	'rss_powered'	 	=> $rss_powered,
	'rss_available' 	=> '',
	'rss_valid' 		=> '',
    'show_search'       => $phpiCal_config->show_search,
	'next_day' 			=> $next_day,
	'next_week' 		=> $next_week,
	'prev_day'	 		=> $prev_day,
	'prev_week'	 		=> $prev_week,
	'show_goto' 		=> '',
	'show_user_login'	=> $show_user_login,
	'invalid_login'		=> $invalid_login,
	'login_querys'		=> $login_querys,
	'is_logged_in' 		=> $is_logged_in,
	'username'			=> $username,
	'logout_querys'		=> $logout_querys,
	'list_icals' 		=> $list_icals,
	'list_icals_pick' 		=> $list_icals_pick,
	'list_years' 		=> $list_years,
	'list_months' 		=> $list_months,
	'list_weeks' 		=> $list_weeks,
	'list_jumps' 		=> $list_jumps,
	'legend'	 		=> $list_calcolors,
	'style_select' 		=> '',
	'l_goprint'			=> $lang['l_goprint'],
	'l_preferences'		=> $lang['l_preferences'],
	'l_calendar'		=> $lang['l_calendar'],
	'l_legend'			=> $lang['l_legend'],
	'l_tomorrows'		=> $lang['l_tomorrows'],
	'l_jump'			=> $lang['l_jump'],
	'l_todo'			=> $lang['l_todo'],
	'l_prev'			=> $lang['l_prev'],
	'l_next'			=> $lang['l_next'],
	'l_day'				=> $lang['l_day'],
	'l_week'			=> $lang['l_week'],
	'l_month'			=> $lang['l_month'],
	'l_year'			=> $lang['l_year'],
	'l_subscribe'		=> $lang['l_subscribe'],
	'l_download'		=> $lang['l_download'],
		'l_search'				=> $lang['l_search'],
	'l_pick_multiple'	=> $lang['l_pick_multiple'],
	'l_powered_by'		=> $lang['l_powered_by'],
	'l_this_site_is'	=> $lang['l_this_site_is']			
	));
	
if ($phpiCal_config->allow_preferences != 'yes') {
	$page->replace_tags(array(
	'allow_preferences'	=> ''
	));
}	
	
if ($phpiCal_config->allow_login == 'yes') {
	$page->replace_tags(array(
	'l_invalid_login'	=> $lang['l_invalid_login'],
	'l_password'		=> $lang['l_password'],
	'l_username'		=> $lang['l_username'],
	'l_login'			=> $lang['l_login'],
	'l_logout'			=> $lang['l_logout']
	));
}
else {
    $page->replace_tags(array('l_logout' => ''));
}

if ($phpiCal_config->show_search != 'yes') {
	$page->nosearch($page);
}	
	
$page->draw_week($page);
$page->tomorrows_events($page);
$page->get_vtodo($page);
$page->draw_subscribe($page);

$page->output();


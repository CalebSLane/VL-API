<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'instructions';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

//summary and summaries
$route['summaries']['get'] = 'summary/index';
$route['summaries/([a-z]+)']['get'] = 'summary/select/$1';
$route['summaries/([a-z]+)']['post'] = 'summary/insert/$1';
$route['summary/([a-z]+)/(:num)']['get'] = 'summary/select/$1/$2';
$route['summary/([a-z]+)/(:num)']['put'] = 'summary/update/$1/$2';
$route['summary/([a-z]+)/(:num)']['delete'] = 'summary/delete/$1/$2';

//suppression and suppressions
$route['suppressions']['get'] = 'suppression/index';
$route['suppressions/([a-z]+)']['get'] = 'suppression/select/$1';
$route['suppressions/([a-z]+)']['post'] = 'suppression/insert/$1';
$route['suppression/([a-z]+)/(:num)']['get'] = 'suppression/select/$1/$2';
$route['suppression/([a-z]+)/(:num)']['put'] = 'suppression/update/$1/$2';
$route['suppression/([a-z]+)/(:num)']['delete'] = 'suppression/delete/$1/$2';

//gender and genders
$route['genders']['get'] = 'gender/index';
$route['genders/([a-z]+)']['get'] = 'gender/select/$1';
$route['genders/([a-z]+)']['post'] = 'gender/insert/$1';
$route['gender/([a-z]+)/(:num)']['get'] = 'gender/select/$1/$2';
$route['gender/([a-z]+)/(:num)']['put'] = 'gender/update/$1/$2';
$route['gender/([a-z]+)/(:num)']['delete'] = 'gender/delete/$1/$2';

//age and ages
$route['ages']['get'] = 'age/index';
$route['ages/([a-z]+)']['get'] = 'age/select/$1';
$route['ages/([a-z]+)']['post'] = 'age/insert/$1';
$route['age/([a-z]+)/(:num)']['get'] = 'age/select/$1/$2';
$route['age/([a-z]+)/(:num)']['put'] = 'age/update/$1/$2';
$route['age/([a-z]+)/(:num)']['delete'] = 'age/delete/$1/$2';

//justification and justifications
$route['justifications']['get'] = 'justification/index';
$route['justifications/([a-z]+)']['get'] = 'justification/select/$1';
$route['justifications/([a-z]+)']['post'] = 'justification/insert/$1';
$route['justification/([a-z]+)/(:num)']['get'] = 'justification/select/$1/$2';
$route['justification/([a-z]+)/(:num)']['put'] = 'justification/update/$1/$2';
$route['justification/([a-z]+)/(:num)']['delete'] = 'justification/delete/$1/$2';

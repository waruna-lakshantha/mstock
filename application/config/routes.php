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
$route['default_controller'] = 'User_Authentication';
$route['user_authentication'] = 'User_Authentication';
$route['form'] = 'Page';
$route['form/load/(:num)'] = 'Page/load/$1';
$route['transaction'] = 'Process';
$route['transaction/updatepr'] = 'Process/update_pr';
$route['transaction/updategrn'] = 'Process/update_grn';
$route['transaction/updatemrn'] = 'Process/update_mrn';
$route['transaction/updatemin'] = 'Process/update_min';
$route['transaction/updateholditem'] = 'Process/update_hold_item';
$route['transaction/approvepr'] = 'Process/approve_rej_pr';
$route['transaction/acceptpr'] = 'Process/accept_rej_pr';
$route['transaction/proceedpr'] = 'Process/proceed_pr';
$route['transaction/approvemrn'] = 'Process/approve_rej_mrn';
$route['transaction/updatejob'] = 'Process/update_job';
$route['transaction/approvejob'] = 'Process/approve_rej_job';
$route['transaction/acceptjob'] = 'Process/accept_rej_job';
$route['transaction/completejob'] = 'Process/complete_job';
$route['transaction/feedbackjob'] = 'Process/feedback_job';
$route['transaction/approvegrn'] = 'Process/approve_grn';
$route['transaction/updateitem'] = 'Process/update_item';
$route['transaction/updateadj'] = 'Process/update_adj';
$route['transaction/updateuser'] = 'Process/update_user';
$route['read/itemstockbalance'] = 'Process/read_loc_wise_item_stock';
$route['read_stk_bal/(:num)'] = 'Get_Data/read_item_stock_bal/$1';
$route['read_stk_bal_com/(:num)/(:num)'] = 'Get_Data/read_item_stock_bal_com/$1/$2';
$route['read_user_permission/(:any)/(:any)'] = 'Get_Data/read_user_permission/$1/$2';
$route['print/(:num)/(:num)'] = 'Print_Doc/view_doc/$1/$2';
$route['view'] = 'View_Report/prepare_report';
$route['upload/do_upload/(:num)'] = 'Upload/do_upload/$1';
$route['logout'] = 'User_Authentication/logout';
$route['form/load/logout'] = 'User_Authentication/logout';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

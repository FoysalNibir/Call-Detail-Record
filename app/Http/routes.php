<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('login', array('as' => 'login', 'uses' => 'LoginController@getLogin'));
Route::post('login', array('as' => 'login.post', 'uses' => 'LoginController@postLogin'));
Route::get('logout', array('as' => 'logout', 'uses' => 'LoginController@getLogout'));


Route::group(['middleware' => 'auth'], function()
{
	Route::get('workspaces', array('as' => 'workspaces', 'uses' => 'WorkSpaceController@workspaces'));
	Route::post('workspaces/store', array('as' => 'workspaces.store', 'uses' => 'WorkSpaceController@store'));
	Route::get('users/create', array('as' => 'users.create', 'uses' => 'UserController@create'));
	Route::post('users/store', array('as' => 'users.store', 'uses' => 'UserController@store'));
	Route::get('workspaces/create', array('as' => 'workspaces.create', 'uses' => 'WorkSpaceController@create'));
	Route::get('dashboard/{id}', array('as' => 'dashboard', 'uses' => 'WorkSpaceController@dashBoard'));
	Route::get('deleteworkspace/{id}', array('as' => 'deleteworkspace', 'uses' => 'WorkSpaceController@deleteworkspace'));

	Route::group(['prefix' => 'targetanalysis'], function()
	{
		Route::get('commoncalls/{id}', array('as' => 'commoncalls', 'uses' => 'TargetController@commoncalls'));
		Route::get('commoncallsfrequency/{id}', array('as' => 'commoncallsfrequency', 'uses' => 'TargetController@commoncallsfrequency'));
		Route::get('commoncallsfrequencyduration/{id}', array('as' => 'commoncallsfrequencyduration', 'uses' => 'TargetController@commoncallsfrequencyduration'));
		Route::get('behaviour/{id}', array('as' => 'behaviour', 'uses' => 'TargetController@behaviour'));
		Route::get('weekdaybehaviour/{id}', array('as' => 'weekdaybehaviour', 'uses' => 'TargetController@weekdaybehaviour'));
		Route::get('opsummary/{id}', array('as' => 'opsummary', 'uses' => 'TargetController@opsummary'));
		Route::get('cellidsummary/{id}', array('as' => 'cellidsummary', 'uses' => 'TargetController@cellidsummary'));
		Route::get('imeisummary/{id}', array('as' => 'imeisummary', 'uses' => 'TargetController@imeisummary'));
		Route::get('fclc/{id}', array('as' => 'fclc', 'uses' => 'TargetController@fclc'));
		Route::get('fclcop/{id}', array('as' => 'fclcop', 'uses' => 'TargetController@fclcop'));
		Route::get('address/{id}', array('as' => 'address', 'uses' => 'TargetController@address'));
		Route::get('datesop/{id}', array('as' => 'datesop', 'uses' => 'TargetController@datesop'));
		Route::get('datescell/{id}', array('as' => 'datescell', 'uses' => 'TargetController@datescell'));
		Route::get('incoming/{id}', array('as' => 'incoming', 'uses' => 'TargetController@incoming'));
		Route::get('outgoing/{id}', array('as' => 'outgoing', 'uses' => 'TargetController@outgoing'));
		Route::get('incomingsms/{id}', array('as' => 'incomingsms', 'uses' => 'TargetController@incomingsms'));
		Route::get('outgoingsms/{id}', array('as' => 'outgoingsms', 'uses' => 'TargetController@outgoingsms'));		
	});


	Route::group(['prefix' => 'toweranalysis'], function()
	{
		Route::get('towersummary/{id}', array('as' => 'towersummary', 'uses' => 'TowerController@towersummary'));
		Route::get('towerdetails/{id}', array('as' => 'towerdetails', 'uses' => 'TowerController@towerdetails'));
		Route::get('commonnumbers/{id}', array('as' => 'commonnumbers', 'uses' => 'TowerController@commonnumbers'));
		Route::get('commonimeis/{id}', array('as' => 'commonimeis', 'uses' => 'TowerController@commonimeis'));
		Route::get('commonop/{id}', array('as' => 'commonop', 'uses' => 'TowerController@commonop'));
		Route::get('internalcalls/{id}', array('as' => 'internalcalls', 'uses' => 'TowerController@internalcalls'));
	});


	Route::group(['prefix' => 'imeianalysis'], function()
	{
		Route::get('imeinumbers/{id}', array('as' => 'imeinumbers', 'uses' => 'ImeiController@imeinumbers'));
		Route::get('imeidetails/{id}', array('as' => 'imeidetails', 'uses' => 'ImeiController@imeidetails'));
		Route::get('imeiusage/{id}', array('as' => 'imeiusage', 'uses' => 'ImeiController@imeiusage'));
		Route::get('commoncallers/{id}', array('as' => 'commoncallers', 'uses' => 'ImeiController@commoncallers'));

	});

	Route::group(['prefix' => 'visualanalysis'], function()
	{
		Route::get('completeanalysis/{id}', array('as' => 'completeanalysis', 'uses' => 'VisualController@completeanalysis'));
		Route::get('editablecompleteanalysis/{id}', array('as' => 'editablecompleteanalysis', 'uses' => 'VisualController@editablecompleteanalysis'));
		Route::get('ultracompleteanalysis/{id}', array('as' => 'ultracompleteanalysis', 'uses' => 'VisualController@ultracompleteanalysis'));
		Route::get('suspectconnections/{id}', array('as' => 'suspectconnections', 'uses' => 'VisualController@suspectconnections'));
		Route::get('commonconnections/{id}', array('as' => 'commonconnections', 'uses' => 'VisualController@commonconnections'));
		Route::get('commonconnectionscluster/{id}', array('as' => 'commonconnectionscluster', 'uses' => 'VisualController@commonconnectionscluster'));

	});


	Route::group(['prefix' => 'glanceanalysis'], function()
	{
		Route::get('targettimeline/{id}', array('as' => 'targettimeline', 'uses' => 'GlanceController@targettimeline'));
		Route::get('targettimelinesms/{id}', array('as' => 'targettimelinesms', 'uses' => 'GlanceController@targettimelinesms'));
		Route::get('allcalls/{id}', array('as' => 'allcalls', 'uses' => 'GlanceController@allcalls'));

	});


	Route::group(['prefix' => 'aianalysis'], function()
	{
		Route::get('conferencecall/{id}', array('as' => 'conferencecall', 'uses' => 'AiController@conferencecall'));

	});


	Route::group(['prefix' => 'locationanalysis'], function()
	{
		Route::get('towerslocation/{id}', array('as' => 'towerslocation', 'uses' => 'LocationController@towerslocation'));
		Route::get('targetlocation/{id}', array('as' => 'targetlocation', 'uses' => 'LocationController@targetlocation'));
		Route::get('multipletargetlocation/{id}', array('as' => 'multipletargetlocation', 'uses' => 'LocationController@multipletargetlocation'));
		Route::get('commonlocation/{id}', array('as' => 'commonlocation', 'uses' => 'LocationController@commonlocation'));
		Route::get('targetroute/{id}', array('as' => 'targetroute', 'uses' => 'LocationController@targetroute'));
		Route::get('targetmovements/{id}', array('as' => 'targetmovements', 'uses' => 'LocationController@targetmovements'));
		Route::get('advtargetmovements/{id}', array('as' => 'advtargetmovements', 'uses' => 'LocationController@advtargetmovements'));

		Route::get('singletarget/{id}', array('as' => 'singletarget', 'uses' => 'LocationController@singletarget'));
		Route::get('singledaymultiple/{id}', array('as' => 'singledaymultiple', 'uses' => 'LocationController@singledaymultiple'));

		Route::get('printlocation/{id}', array('as' => 'printlocation', 'uses' => 'LocationPrintController@printlocation'));


	});


	Route::group(['prefix' => 'othernumber'], function()
	{
		Route::get('calculation/{id}', array('as' => 'calculation', 'uses' => 'OtherNumberController@calculation'));
	});

	Route::group(['prefix' => 'deletion'], function()
	{
		Route::get('deletebparty/{id}', array('as' => 'deletebparty', 'uses' => 'DeletionController@deletebparty'));
		Route::get('deleteimei/{id}', array('as' => 'deleteimei', 'uses' => 'DeletionController@deleteimei'));
		Route::get('deletecell/{id}', array('as' => 'deletecell', 'uses' => 'DeletionController@deletecell'));
		Route::get('addservice/{id}', array('as' => 'addservice', 'uses' => 'DeletionController@addservice'));
		Route::get('autoaddservice/{id}', array('as' => 'autoaddservice', 'uses' => 'DeletionController@autoaddservice'));
		Route::get('deletetower/{id}', array('as' => 'deletetower', 'uses' => 'DeletionController@deletetower'));
	});


	Route::get('posttower/{id}', array('as' => 'posttower', 'uses' => 'ImportController@posttower'));
	Route::get('postcdr/{id}', array('as' => 'postcdr', 'uses' => 'ImportController@postcdr'));
    Route::post('createreport/{id}', array('as' => 'createreport', 'uses' => 'ReportController@createreport'));





});



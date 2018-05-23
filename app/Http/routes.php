<?php

Route::group(['middleware' => ['web']], function () {
    Route::get('/', 'PagesController@index');
    /* ----------------------------------------------------------USER CONTROLLER
     * Signin | Logout | Reset password
     */
    Route::get('reset-password', 'UserController@getReset');
    Route::post('reset-password', 'UserController@beginReset');
    Route::get('reset-password/{token}', 'UserController@endReset');
    Route::post('update-password', 'UserController@updatePassword');
    Route::controller('user', 'UserController');
    /* -----------------------------------------------------------USERS RESOURCE
     * Show | Create | Edit | Archive
     */
    Route::resource('users', 'UsersController');
    /* -----------------------------------------------------------BANKS RESOURCE
     * Show | Create | Edit | Archive
     */
    Route::resource('banks', 'BanksController');
    /* -----------------------------------------------------------WIRES RESOURCE
     * Show | Create | Edit | Archive
     */
    Route::resource('wires', 'WiresController');
    /* -------------------------------------------------------MERCHANTS RESOURCE
     * Show | Create | Edit | Archive
     */
    Route::resource('companies', 'MerchantsController');
    /* ------------------------------------------------------------------UPDATES
     * Show
     */
    Route::get('last-updates', 'UpdatesController@getUpdates');
    /* -------------------------------------------------------------STATIC PAGES
     *  Contact Us | Additional Info | Statistics | Billing
     */
    Route::get('billing', 'PagesController@billing');
    Route::get('statistics', 'PagesController@getStatistics');
    Route::get('contact-us', 'PagesController@contactUs');
    Route::get('additional-info', 'PagesController@additionalInfo');
});

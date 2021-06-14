<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/signin', 'AuthController@signin')->name('login.signin');
Route::get('/callback', 'AuthController@callback');
Route::get('/callback_test', 'AuthController@callback_test')->name('login.signin_test');
Route::get('/signout', 'AuthController@signout')->name('login.signout');
Route::get('/login', 'Auth\LoginController@index')->name('login.index');
Route::get('/suspended', 'Auth\LoginController@suspended')->name('login.suspended');
Route::get('/confirm/booking/{code}', 'UserBookingController@confirm')->name('booking.confirm');

Route::group(['prefix' => '/','middleware' => 'checkuser'], function() {
    Route::get('', 'UserBookingController@myBooking')->name('home.index');
    Route::group(['prefix' => 'manage'], function() {
        Route::get('booking', 'UserBookingController@index')->name('home.booking.index')->middleware('checkadmin');
        Route::get('booking/get/{id}', 'UserBookingController@file')->name('home.booking.file')->middleware('checkadmin');
        Route::get('booking/status/{status}', 'UserBookingController@index')->name('home.booking.status')->middleware('checkadmin');
        Route::post('accept', 'UserBookingController@accept')->name('home.booking.accept')->middleware('checkadmin');
        Route::post('decline', 'UserBookingController@decline')->name('home.booking.decline')->middleware('checkadmin');
        Route::post('prompt/revise', 'UserBookingController@prompt')->name('home.booking.prompt')->middleware('checkadmin');
        Route::post('cancel', 'UserBookingController@cancel')->name('home.booking.cancel');
        Route::post('revise', 'UserBookingController@revise')->name('home.booking.revise');
    });
    Route::group(['prefix' => '/master-facility'], function() {
        Route::get('synchronize', 'MasterFacilityController@synchronize')->name('home.master.synchronize');
        Route::get('', 'MasterFacilityController@index')->name('home.master.facility')->middleware('checkadmin');
        Route::get('catalogue/{id}', 'MasterFacilityController@catalogue')->name('home.master.catalogue');
        Route::get('catalogue/edit/{id}', 'MasterFacilityController@revise')->name('home.master.revise');
        Route::get('edit/{id}', 'MasterFacilityController@edit')->name('home.master.facility.edit');
        Route::post('upload/img', 'MasterFacilityController@uploadImage')->name('home.master.facility.image');
        Route::get('delete/img/{id}/{img}', 'MasterFacilityController@deleteImage')->name('home.master.facility.image.delete');
        Route::post('update', 'MasterFacilityController@update')->name('home.master.facility.update');
        Route::post('book', 'UserBookingController@store')->name('home.master.book');
        Route::get('search', 'MasterFacilityController@search')->name('home.master.search');
        Route::post('search', 'MasterFacilityController@search_query')->name('home.master.query');
        Route::get('qr', 'MasterFacilityController@generateQr')->name('MasterFacility.qr')->middleware('checkadmin');
        // Route::get('export/{code}', 'MasterFacilityController@export')->name('home.master.facility.export')->middleware('checkadmin');
        Route::get('timetable/{id}', 'MasterFacilityController@timetable')->name('home.master.facility.timetable')->middleware('checkadmin');
        Route::post('import', 'MasterFacilityController@import')->name('MasterFacility.import')->middleware('checkadmin');
    });

    Route::group(['prefix' => '/master-equipment'], function() {
        Route::get('', 'MasterEquipmentController@index')->name('home.master.equipment')->middleware('checkadmin');
        Route::get('export', 'MasterEquipmentController@export')->name('MasterEquipment.export')->middleware('checkadmin');
        Route::post('import', 'MasterEquipmentController@import')->name('MasterEquipment.import')->middleware('checkadmin');
    });

    Route::group(['prefix' => '/master-period'], function() {
        Route::get('', 'MasterPeriodController@index')->name('home.master.period')->middleware('checkadmin');
        Route::post('import', 'MasterPeriodController@import')->name('masterPeriod.import')->middleware('checkadmin');
    });

    Route::group(['prefix' => '/master-facility-equipment'], function() {
        Route::get('', 'MasterFacilityEquipmentController@index')->name('home.master.facility.equipment')->middleware('checkadmin');
        Route::get('export', 'MasterFacilityEquipmentController@export')->name('MasterFacilityEquipment.export')->middleware('checkadmin');
        Route::post('import', 'MasterFacilityEquipmentController@import')->name('MasterFacilityEquipment.import')->middleware('checkadmin');
    });

    Route::group(['prefix' => '/monitor-class'], function() {
        Route::get('', 'MonitorClassController@index')->name('home.monitor.class')->middleware('checkadmin');
        Route::get('synchronize', 'MonitorClassController@synchronize')->name('home.monitor.synchronize')->middleware('checkadmin');
        Route::get('export', 'MonitorClassController@export')->name('MasterMonitorClass.export')->middleware('checkadmin');
        Route::post('import', 'MonitorClassController@import')->name('MasterMonitorClass.import')->middleware('checkadmin');
    });

    Route::group(['prefix' => '/schedule'], function() {
        Route::get('', 'ScheduleController@index')->name('home.schedule')->middleware('checkadmin');
        Route::get('finalize', 'ScheduleController@store')->name('home.schedule.store')->middleware('checkadmin');
    });

    Route::group(['prefix' => '/user'], function() {
        Route::get('', 'UserController@index')->name('home.user')->middleware('checkadmin', 'checkfacultyadmin');
        Route::get('status/{id}', 'UserController@toggleStatus')->name('home.user.status')->middleware('checkadmin', 'checkfacultyadmin');
        Route::get('role/{id}', 'UserController@toggleRole')->name('home.user.role')->middleware('checkadmin', 'checkfacultyadmin');
        Route::get('relasi_faculty', 'UserController@relasi_faculty')->name('home.user.relasi_faculty')->middleware('checkadmin', 'checkfacultyadmin');
        Route::get('relasi_faculty_edit/{id}', 'UserController@relasi_faculty_edit')->name('home.user.relasi_faculty_edit')->middleware('checkadmin', 'checkfacultyadmin');
        Route::post('relasi_faculty_update', 'UserController@relasi_faculty_update')->name('home.user.faculty.update')->middleware('checkadmin', 'checkfacultyadmin');
    });

    Route::group(['prefix' => '/config'], function() {
        Route::get('', 'MasterConfigController@index')->name('home.config')->middleware('checkadmin');
        Route::post('store', 'MasterConfigController@store')->name('home.config.store')->middleware('checkadmin', 'checkfacultyadmin');
    });

    Route::group(['prefix' => 'calender'], function() {
        Route::get('/{id}',"CalenderController@index")->name('index.calender');
        Route::post('create','CalenderController@create')->name('create.calender');
        // Route::get('delete','CalenderController@deleteAllCalendar')->name('delete.calender');
        // Route::get('deleteGroups','CalenderController@DeleteAllCalendarGroup')->name('delete.calender.groups');
    });
});



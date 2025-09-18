<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" ->middleware group. Now create something great!
|
*/

Route::get('/', [LoginController::class, 'authenticate'])->middleware('auth')->name('index');
Route::get('/accessDenied', [LoginController::class, 'accessDenied'])->middleware('auth')->name('accessDenied');

/////////////////////////ROUTE ADMIN //////////////////////////////////////
Route::get('/admin', [AdminController::class, 'index'])->middleware('auth')->name('indexADM');
///////////////////////////////////////////////////////////////////////////

/////////////////////////ROUTE ACCOUNTANT //////////////////////////////////////
Route::get('/accountantTransac', [AccountantController::class, 'getTransactions'])->middleware('auth')->name('getTransactions');

Route::get('/accountantTransac/{idTransaction}', [AccountantController::class, 'exportTransacToPDF'])->middleware('auth')->name('exportTransacToPDF');


/////////////////////////ROUTE MECHANIC //////////////////////////////////////
Route::get('/mechanicList', [MechanicController::class, 'getCars'])->middleware('auth')->name('listCar');

Route::get('/mechanicAddCar', [MechanicController::class, 'addCar'])->middleware('auth')->name('addCarGet');

Route::post('/mechanicAddCar', [MechanicController::class, 'storeCar'])->middleware('auth')->name('addCarPost');

Route::get('/editCar/{idCar}', [MechanicController::class, 'editCar'])->middleware('auth')->name('editCarGet');

Route::post('/editCar/{idCar}', [MechanicController::class, 'storeUpdateCar'])->middleware('auth')->name('editCarPost');

Route::get('/mechanicList/delete/{idCar}', [MechanicController::class, 'deleteCar'])->middleware('auth')->name('deleteCar');

/////////////////////////ROUTE DRIVER //////////////////////////////////////
Route::get('/driver', [DriverController::class, 'listDriver'])->middleware('auth')->name('listCourse');

Route::get('/courseEffectuee/{idTrans}', [DriverController::class, 'validateRun'])->middleware('auth')->name('courseValide');

Route::get('/updateActivity', [DriverController::class, 'updateActivity'])->middleware('auth')->name('updateActivity');
////////////////////////ROUTE HR //////////////////////////////////////

Route::get('/HR',[HRController::class, 'initHRView'])->middleware('auth')->name('indexHR');

Route::get('/deleteEmploye/{idEmploye}', [HRController::class, 'deleteEmploye'])->middleware('auth')->name('initDeleteEmploye');

Route::get('/initFormEmploye/{idEmploye}', [HRController::class, 'initFormEmploye'])->middleware('auth')->name('initUpdateEmploye');

Route::get('/initFormPayslip/{idEmploye}', [HRController::class, 'initFormPayslip'])->middleware('auth')->name('initPayslip');

Route::get('/createEmploye', [HRController::class, 'initCreateFormEmploye'])->middleware('auth')->name('createHREmploye');

Route::post('/updateEmploye', [HRController::class, 'updateEmploye'])->middleware('auth')->name('updateHREmploye');

Route::post('/generatePDF', [HRController::class, 'generatePDF'])->middleware('auth')->name('createPDF');

/////////////////////////ROUTE HOTLINER //////////////////////////////////////
Route::get('/hotliner', [HotlinerController::class, 'homeHotliner'])->middleware('auth')->name('hotliner');

Route::post('/paiement', [HotlinerController::class, 'clientPaiement'])->middleware('auth')->name('paiement');

Route::post('/confirmation', [HotlinerController::class, 'formValidation'])->middleware('auth')->name('confirmation');

Route::get('/createCourse', [HotlinerController::class, 'createCourse'])->middleware('auth')->name('createCourse');

Route::post('/courseEdit', [HotlinerController::class, 'courseEdit'])->middleware('auth')->name('editCourse');

Route::post('/saveEdit', [HotlinerController::class, 'saveEdit'])->middleware('auth')->name('saveEdit');

Route::post('/delCourse', [HotlinerController::class, 'delCourse'])->middleware('auth')->name('delCourse');

Route::get('/remboursement/{id}', [HotlinerController::class, 'remboursement'])->middleware('auth')->name('remboursement');
///////////////////////////////////////////////////////////////////////////////////

<?php
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\FreezerController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\VaccineController;
use App\Http\Controllers\WarehouseController;
use Illuminate\Support\Facades\Route;
Route::middleware('guest')->group(function () {
 Route::get('/', [AuthenticatedSessionController::class,'create'])->name('login');
 Route::get('/logowanie', [AuthenticatedSessionController::class,'create']);
 Route::post('/logowanie',[AuthenticatedSessionController::class,'store'])->name('login.store');
 Route::get('/rejestracja',[RegisteredUserController::class,'create'])->name('register');
 Route::post('/rejestracja',[RegisteredUserController::class,'store'])->name('register.store');
});
Route::middleware('auth')->group(function () {
 Route::view('/panel','dashboard')->name('dashboard');
 Route::post('/wyloguj',[AuthenticatedSessionController::class,'destroy'])->name('logout');
 Route::view('/administracja','admin.index')->middleware('role:admin')->name('admin.index');
 Route::middleware('role:admin')->prefix('administracja/uzytkownicy')->name('admin.users.')->group(function () {
  Route::get('/',[UserController::class,'index'])->name('index'); Route::get('/dodaj',[UserController::class,'create'])->name('create'); Route::post('/',[UserController::class,'store'])->name('store'); Route::get('/{user}/edytuj',[UserController::class,'edit'])->name('edit'); Route::put('/{user}',[UserController::class,'update'])->name('update');
 });
 Route::prefix('ustawienia')->name('settings.')->group(function () {
  Route::get('/zmiana-hasla',[SettingsController::class,'requiredPassword'])->name('password.required');
  Route::get('/',[SettingsController::class,'index'])->name('index');
  Route::put('/haslo',[SettingsController::class,'updatePassword'])->name('password.update');
  Route::get('/wiadomosci/{message}',[SettingsController::class,'showMessage'])->name('messages.show');
 });
 Route::view('/raporty','reports.index')->middleware('role:admin,manager')->name('reports.index');
 Route::prefix('magazyn-owocowy')->name('warehouse.')->group(function () {
  Route::get('/',[WarehouseController::class,'products'])->name('products');
  Route::post('/produkty',[WarehouseController::class,'storeProduct'])->middleware('role:admin,manager')->name('products.store');
  Route::get('/produkty/{product}',[WarehouseController::class,'flavors'])->name('flavors');
  Route::post('/produkty/{product}/smaki',[WarehouseController::class,'storeFlavor'])->name('flavors.store');
  Route::get('/produkty/{product}/smaki/{flavor}',[WarehouseController::class,'containers'])->name('containers');
  Route::post('/produkty/{product}/smaki/{flavor}/kontenery',[WarehouseController::class,'storeContainer'])->name('containers.store');
  Route::get('/kontenery/{container}',[WarehouseController::class,'showContainer'])->name('container');
  Route::post('/kontenery/{container}/zuzycie',[WarehouseController::class,'storeUsage'])->name('usages.store');
 });
 Route::prefix('szczepionki')->name('vaccines.')->group(function () {
  Route::get('/',[VaccineController::class,'index'])->name('index');
  Route::post('/',[VaccineController::class,'store'])->name('store');
  Route::post('/typy',[VaccineController::class,'storeType'])->name('types.store');
  Route::get('/{lot}',[VaccineController::class,'show'])->name('show');
  Route::post('/{lot}/pobrania',[VaccineController::class,'storeUsage'])->name('usages.store');
 });
 Route::prefix('zamrazarki')->name('freezers.')->group(function () {
  Route::get('/',[FreezerController::class,'index'])->name('index');
  Route::post('/',[FreezerController::class,'store'])->name('store');
  Route::get('/{freezer}',[FreezerController::class,'show'])->name('show');
  Route::post('/{freezer}/typ',[FreezerController::class,'assignType'])->name('assignments.store');
  Route::post('/{freezer}/oproznij',[FreezerController::class,'endAssignment'])->name('assignments.end');
  Route::post('/{freezer}/temperatura',[FreezerController::class,'storeTemperature'])->name('temperature.store');
  Route::post('/{freezer}/mycie',[FreezerController::class,'storeCleaning'])->name('cleaning.store');
 });
});

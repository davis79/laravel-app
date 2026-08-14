<?php
namespace App\Providers;
use App\Models\FruitProduct;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
class AppServiceProvider extends ServiceProvider
{
 public function register(): void {}
 public function boot(): void
 {
  View::composer(['dashboard','components.warehouse-shell'],function($view){
   $view->with('warehouseTreeProducts',FruitProduct::query()->with(['flavors'=>fn($query)=>$query->orderBy('name')])->orderBy('name')->get());
  });
 }
}

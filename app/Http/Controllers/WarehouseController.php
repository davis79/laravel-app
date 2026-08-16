<?php
namespace App\Http\Controllers;
use App\Models\FruitContainer;
use App\Models\FruitFlavor;
use App\Models\FruitProduct;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
class WarehouseController extends Controller
{
 public function products(): View { $products=FruitProduct::withCount('flavors')->with(['flavors'=>fn($q)=>$q->withCount('containers')])->get(); return view('warehouse.products',compact('products')); }
 public function storeProduct(Request $request): RedirectResponse { FruitProduct::create($request->validate(['name'=>['required','max:255','unique:fruit_products'],'description'=>['nullable','max:1000']])); return back()->with('status','Produkt został dodany.'); }
 public function flavors(FruitProduct $product): View { $product->load(['flavors'=>fn($q)=>$q->withCount('containers')->withSum('containers','weight_kg')]); return view('warehouse.flavors',compact('product')); }
 public function storeFlavor(Request $request,FruitProduct $product): RedirectResponse { $v=$request->validate(['name'=>['required','max:255',Rule::unique('fruit_flavors')->where('fruit_product_id',$product->id)],'color'=>['nullable',Rule::in(['indigo','cyan','emerald','amber','rose','violet'])]]); $product->flavors()->create($v); return back()->with('status','Smak został dodany.'); }
 public function containers(FruitProduct $product,FruitFlavor $flavor): View
 {
  abort_unless($flavor->fruit_product_id===$product->id,404);
  $containers=$flavor->containers()
   ->withSum('usages','quantity_kg')
   ->orderByRaw('(weight_kg - COALESCE((SELECT SUM(quantity_kg) FROM fruit_container_usages WHERE fruit_container_usages.fruit_container_id = fruit_containers.id), 0)) <= 0')
   ->latest('received_at')
   ->paginate(20);
  $totalWeight=(float)$flavor->containers()->sum('weight_kg');
  $totalUsed=(float)DB::table('fruit_container_usages')->join('fruit_containers','fruit_containers.id','=','fruit_container_usages.fruit_container_id')->where('fruit_containers.fruit_flavor_id',$flavor->id)->sum('fruit_container_usages.quantity_kg');
  $remainingWeight=max(0,$totalWeight-$totalUsed);
  return view('warehouse.containers',compact('product','flavor','containers','totalWeight','totalUsed','remainingWeight'));
 }
 public function storeContainer(Request $request,FruitProduct $product,FruitFlavor $flavor): RedirectResponse
 {
  abort_unless($flavor->fruit_product_id===$product->id,404);
  $validated=$request->validate([
   'containers'=>['required','array','min:1','max:100'],
   'containers.*.container_number'=>['required','max:255','distinct','unique:fruit_containers,container_number'],
   'containers.*.received_at'=>['required','date'],
   'containers.*.expires_at'=>['required','date'],
   'containers.*.weight_kg'=>['required','numeric','gt:0','decimal:0,3'],
  ],['containers.*.container_number.distinct'=>'Numery kontenerów w jednej partii nie mogą się powtarzać.']);
  foreach($validated['containers'] as $index=>$container){ if($container['expires_at']<$container['received_at']) throw ValidationException::withMessages(["containers.$index.expires_at"=>'Data ważności nie może być wcześniejsza niż data przyjęcia.']); }
  DB::transaction(fn()=>$flavor->containers()->createMany($validated['containers']));
  return back()->with('status','Dodano kontenery: '.count($validated['containers']).'.');
 }
 public function showContainer(FruitContainer $container): View { $container->load(['flavor.product','usages.recorder'])->loadSum('usages','quantity_kg'); return view('warehouse.container',compact('container')); }
 public function storeUsage(Request $request,FruitContainer $container): RedirectResponse
 {
  $v=$request->validate(['production_number'=>['required','max:255'],'quantity_kg'=>['required','numeric','gt:0','decimal:0,3'],'used_at'=>['required','date'],'notes'=>['nullable','max:2000']]);
  $v['production_name']=$v['production_number'];
  DB::transaction(function()use($v,$container,$request){$locked=FruitContainer::query()->lockForUpdate()->findOrFail($container->id);$used=(float)$locked->usages()->sum('quantity_kg');if($used >= (float)$locked->weight_kg)throw ValidationException::withMessages(['quantity_kg'=>'Kontener jest nieaktywny — cała jego zawartość została zużyta.']);if($used+(float)$v['quantity_kg']>(float)$locked->weight_kg)throw ValidationException::withMessages(['quantity_kg'=>'Ilość przekracza pozostałą wagę kontenera.']);$locked->usages()->create($v+['recorded_by'=>$request->user()->id]);});
  return back()->with('status','Zużycie zostało zapisane.');
 }
}

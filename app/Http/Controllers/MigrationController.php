<?php

namespace App\Http\Controllers;

use App\Models\Pattern;
use App\Models\Supplier;
use App\Models\Tyre;
use App\Models\TyreBrand;
use App\Models\TyreSize;

class MigrationController extends Controller
{
    public function tyres()
    {
        return view('migrations.tyres');
    }

    public function tyres_migrate()
    {
        $tyre_sizes = TyreSize::all();
        $brands = TyreBrand::all();
        $patterns = Pattern::all();
        $suppliers = Supplier::all();
        $tyres = Tyre::all();

        foreach ($tyres as $tyre) {
            foreach ($tyre_sizes as $tyre_size) {
                if ($tyre->TyreSize == $tyre_size->description) {
                    $tyre->size_id = $tyre_size->id;
                    $tyre->save();
                }
            }
        }

        foreach ($tyres as $tyre) {
            foreach ($brands as $brand) {
                if ($tyre->TyreManufName == $brand->name) {
                    $tyre->brand_id = $brand->id;
                    $tyre->save();
                }
            }
        }

        foreach ($tyres as $tyre) {
            foreach ($patterns as $pattern) {
                if ($tyre->TyrePattern == $pattern->name) {
                    $tyre->pattern_id = $pattern->id;
                    $tyre->save();
                }
            }
        }

        foreach ($tyres as $tyre) {
            foreach ($suppliers as $supplier) {
                if ($tyre->TyreVendor == $supplier->name) {
                    $tyre->supplier_id = $supplier->id;
                    $tyre->save();
                }
            }
        }

        return redirect()->route('migrations.tyres')->with('success', 'Tyre migration successful.');
    }
}

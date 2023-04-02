<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Contact;
use App\Models\Document;
use App\Models\LegalitasType;
use App\Models\Specification;
use App\Models\Supplier;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class SupplierController extends Controller
{
    public function index()
    {
        return view('suppliers.index');
    }

    public function create()
    {
        $badan_hukum = ['PT', 'CV', 'UD', 'Koperasi', 'Yayasan', 'Perorangan', 'Lainnya'];
        $specifications = Specification::orderBy('name', 'asc')->get();
        $brands = Brand::orderBy('name', 'asc')->get();
        $document_types = LegalitasType::orderBy('name', 'asc')->get();
        $nomor = $this->nomor_registrasi();

        return view('suppliers.create', compact('nomor', 'badan_hukum', 'specifications', 'brands', 'document_types'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'experience' => ['required', 'numeric', 'min:1900', 'max:2099'],
            'badan_hukum' => 'required',
        ]);

        $supplier = new Supplier();
        $supplier->reg_no = $this->nomor_registrasi();
        $supplier->name = $request->name;
        $supplier->sap_code = $request->sap_code;
        $supplier->badan_hukum = $request->badan_hukum;
        $supplier->npwp = $request->npwp;
        $supplier->email = $request->email;
        $supplier->phone = $request->phone;
        $supplier->website = $request->website;
        $supplier->address1 = $request->address1;
        $supplier->address2 = $request->address2;
        $supplier->city = $request->city;
        $supplier->province = $request->province;
        $supplier->postal_code = $request->postal_code;
        $supplier->country = $request->country;
        $supplier->experience = $request->experience;
        $supplier->jumlah_karyawan = $request->jumlah_karyawan;
        $supplier->status = $request->status;
        $supplier->remarks = $request->remarks;
        $supplier->created_by = auth()->user()->id;
        $supplier->save();

        // if specification is not empty
        if ($request->specifications) {
            foreach ($request->specifications as $specification) {
                $supplier->specifications()->attach($specification);
            }
        }

        // if brand is not empty
        if ($request->brands) {
            foreach ($request->brands as $brand) {
                $supplier->brands()->attach($brand);
            }
        }

        // save branches
        if ($request->contacts) {
            foreach ($request->contacts as $contact) {
                $supplier->contacts()->create([
                    'name' => $contact['name'],
                    'position' => $contact['position'],
                    'email' => $contact['email'],
                    'phone' => $contact['phone'],
                ]);
            }
        }

        return redirect()->route('suppliers.index')->with('success', 'Data created successfully.');
    }

    public function show($id)
    {
        $supplier = Supplier::findOrFail($id);
        $address = $supplier->address1 . ', ' . $supplier->address2 . ', ' . $supplier->city . ', ' . $supplier->province . ', ' . $supplier->postal_code;
        // legalitas if exists
        $legalitas = Document::where('supplier_id', $id)->get();

        return view('suppliers.show', compact('supplier', 'address', 'legalitas'));
    }

    public function edit($id)
    {
        $supplier = Supplier::findOrFail($id);
        $badan_hukum = ['PT', 'CV', 'UD', 'Koperasi', 'Yayasan', 'Perorangan', 'Lainnya'];
        $specifications = Specification::orderBy('name', 'asc')->get();
        $brands = Brand::orderBy('name', 'asc')->get();
        $document_types = LegalitasType::orderBy('name', 'asc')->get();

        return view('suppliers.edit', compact('supplier', 'badan_hukum', 'specifications', 'brands', 'document_types'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'experience' => ['required', 'numeric', 'min:1900', 'max:2099'],
            'badan_hukum' => 'required',
        ]);

        $supplier = Supplier::findOrFail($id);
        $supplier->name = $request->name;
        $supplier->sap_code = $request->sap_code;
        $supplier->badan_hukum = $request->badan_hukum;
        $supplier->npwp = $request->npwp;
        $supplier->email = $request->email;
        $supplier->phone = $request->phone;
        $supplier->website = $request->website;
        $supplier->address1 = $request->address1;
        $supplier->address2 = $request->address2;
        $supplier->city = $request->city;
        $supplier->province = $request->province;
        $supplier->postal_code = $request->postal_code;
        $supplier->country = $request->country;
        $supplier->experience = $request->experience;
        $supplier->jumlah_karyawan = $request->jumlah_karyawan;
        $supplier->status = $request->status;
        $supplier->remarks = $request->remarks;
        $supplier->save();

        // if specification is not empty
        if ($request->specifications) {
            $supplier->specifications()->sync($request->specifications);
        } else {
            $supplier->specifications()->detach();
        }

        // if brand is not empty
        if ($request->brands) {
            $supplier->brands()->sync($request->brands);
        } else {
            $supplier->brands()->detach();
        }

        return redirect()->route('suppliers.edit', $id)->with('success', 'Data updated successfully.');
    }

    public function destroy($id)
    {
        $supplier = Supplier::findOrFail($id);

        // delete all contacts
        $supplier->contacts()->delete();

        // delete all documents
        $supplier->documents()->delete();

        // delete all specifications
        $supplier->specifications()->detach();

        // delete all brands
        $supplier->brands()->detach();

        // delete supplier
        $supplier->delete();

        return redirect()->route('suppliers.index')->with('success', 'Data deleted successfully.');
    }

    public function contact_store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'phone' => 'required',
        ]);

        $supplier = Supplier::findOrFail($request->supplier_id);
        $supplier->contacts()->create([
            'name' => $request->name,
            'position' => $request->position,
            'email' => $request->email,
            'phone' => $request->phone,
        ]);

        return redirect()->route('suppliers.edit', $request->supplier_id)->with('success', 'Contact added successfully');
    }

    public function contact_update(Request $request, $contact_id)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'phone' => 'required',
        ]);

        $contact = Contact::findOrFail($contact_id);
        $contact->name = $request->name;
        $contact->position = $request->position;
        $contact->email = $request->email;
        $contact->phone = $request->phone;
        $contact->save();

        return redirect()->route('suppliers.edit', $contact->supplier_id)->with('success', 'Contact updated successfully');
    }

    public function contact_destroy($contact_id)
    {
        $contact = Contact::findOrFail($contact_id);
        $supplier_id = $contact->supplier_id;
        $contact->delete();

        return redirect()->route('suppliers.edit', $supplier_id)->with('success', 'Contact deleted successfully');
    }

    public function legalitas($supplier_id)
    {
        $supplier = Supplier::findOrFail($supplier_id);
        $legalitas = Document::where('supplier_id', $supplier_id)->get();
        $document_types = LegalitasType::orderBy('name', 'asc')->get();

        return view('suppliers.legalitas', compact('supplier', 'legalitas', 'document_types'));
    }

    public function legalitas_store(Request $request)
    {
        $request->validate([
            'document_type' => 'required',
            'document_no' => 'required',
        ]);

        $legalitas = new Document();
        $legalitas->supplier_id = $request->supplier_id;
        $legalitas->number = $request->document_no;
        $legalitas->type = $request->document_type;
        $legalitas->remarks = $request->remarks;

        if ($request->file_upload) {
            $file = $request->file('file_upload');
            $filename = rand() . '_' . $file->getClientOriginalName();
            $file->move(public_path('document_upload'), $filename);
        } else {
            $filename = null;
        }

        $legalitas->filename = $filename;
        $legalitas->created_by = auth()->user()->id;
        $legalitas->save();

        return redirect()->route('suppliers.legalitas', $request->supplier_id)->with('success', 'Data berhasil disimpan');
    }

    public function legalitas_update(Request $request, $document_id)
    {
        $request->validate([
            'document_no' => 'required',
        ]);

        $legalitas = Document::findOrFail($document_id);
        $legalitas->number = $request->document_no;
        $legalitas->type = $request->document_type;
        $legalitas->remarks = $request->remarks;

        if ($request->file_upload) {
            $file = $request->file('file_upload');
            $filename = rand() . '_' . $file->getClientOriginalName();
            $file->move(public_path('document_upload'), $filename);
            $legalitas->filename = $filename;
        }

        $legalitas->save();

        return redirect()->route('suppliers.legalitas', $request->supplier_id)->with('success', 'Data berhasil disimpan');
    }

    public function legalitas_destroy($document_id)
    {
        $legalitas = Document::findOrFail($document_id);
        $supplier_id = $legalitas->supplier_id;
        $legalitas->delete();

        return redirect()->route('suppliers.legalitas', $supplier_id)->with('success', 'Data berhasil dihapus');
    }

    public function data()
    {
        $suppliers = Supplier::orderBy('name', 'asc')->get();

        return datatables()->of($suppliers)
            ->editColumn('experience', function ($suppliers) {
                $today_year = Carbon::now()->addHours(8)->format('Y');
                return $suppliers->experience ? $today_year - $suppliers->experience . ' Thn' : '-';
            })
            ->editColumn('status', function ($suppliers) {
                if ($suppliers->status == 'active') {
                    return '<span class="badge badge-success">active</span>';
                } elseif ($suppliers->status == 'banned') {
                    return '<span class="badge badge-danger">Banned</span>';
                } else {
                    return '<span class="badge badge-warning">in-active</span>';
                }
            })
            ->editColumn('name', function ($supplier) {
                return $supplier->name . ', ' . $supplier->badan_hukum;
            })
            ->addColumn('specifications', function ($supplier) {
                $specifications = '';
                foreach ($supplier->specifications as $specification) {
                    $specifications .= '<span class="badge badge-info">' . $specification->name . '</span> ';
                }
                return $specifications;
            })
            ->addColumn('action', 'suppliers.action')
            ->addIndexColumn()
            ->rawColumns(['action', 'status', 'name', 'specifications'])
            ->toJson();
    }

    public function nomor_registrasi()
    {
        return str_pad(Supplier::count() + 1, 3, '0', STR_PAD_LEFT) . '/PRC/REG-VENDOR/' . Carbon::now()->addHours(8)->format('y');
    }
}

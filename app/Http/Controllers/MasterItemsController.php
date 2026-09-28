<?php

namespace App\Http\Controllers;

use App\Models\MasterItem;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
class MasterItemsController extends Controller
{
    public function index()
    {
        return view('master_items.index.index');
    }

    public function search(Request $request)
    {
        $kode = $request->kode;
        $nama = $request->nama;
        $hargamin = $request->hargamin;
        $hargamax = $request->hargamax;

        $data_search = MasterItem::query();

        if (!empty($kode)) $data_search = $data_search->where('kode', $kode);
        if (!empty($nama)) $data_search = $data_search->where('nama', 'LIKE', '%' . $nama . '%');
        if (!empty($hargamin)) $data_search = $data_search->where('harga_beli', '>=', $hargamin);
        if (!empty($hargamax)) $data_search = $data_search->where('harga_beli', '<=', $hargamax);


        $data_search = $data_search->select('kode', 'nama', 'jenis', 'harga_beli', 'laba', 'supplier', 'foto')->orderBy('id')->get();


        return json_encode([
            'status' => 200,
            'data' => $data_search
        ]);
    }

    public function formView($method, $id = 0)
    {
        if ($method == 'new') {
            $item = [];
            $selected_kategori = [];
        } else {
            $item = MasterItem::find($id);
            $selected_kategori = $item->kategoris->pluck('id')->toArray();
        }
        $data['item'] = $item;
        $data['method'] = $method;
        $data['kategoris'] = Kategori::orderBy('nama')->get();
        $data['selected_kategori'] = $selected_kategori;
        return view('master_items.form.index', $data);
    }

    public function singleView($kode)
    {
        $data['data'] = MasterItem::where('kode', $kode)->first();
        return view('master_items.single.index', $data);
    }

    public function formSubmit(Request $request, $method, $id = 0)
    {
        if ($method == 'new') {
            $data_item = new MasterItem;
            $kode = MasterItem::count('id');
            $kode = $kode + 1;
            $kode = str_pad($kode, 5, '0', STR_PAD_LEFT);
            sleep(3);
        } else {
            $data_item = MasterItem::find($id);
            $kode = $data_item->kode;
        }

        $data_item->nama = $request->nama;
        $data_item->harga_beli = $request->harga_beli;
        $data_item->laba = $request->laba;
        $data_item->kode = $kode;
        $data_item->supplier = $request->supplier;
        $data_item->jenis = $request->jenis;
        if ($request->hasFile('foto')) {
            if (!empty($data_item->foto)) {
                Storage::disk('public')->delete($data_item->foto);
            }
            $data_item->foto = $request->file('foto')->store('master_items', 'public');
        }
        $data_item->save();
        $data_item->kategoris()->sync($request->kategori ?? []);

        return redirect('master-items');
    }

    public function delete($id)
    {
        MasterItem::find($id)->delete();
        return redirect('master-items');
    }

    public function exportExcel()
    {
        $items = MasterItem::with('kategoris')->orderBy('id')->get();

        $html = view('master_items.index.excel', ['items' => $items])->render();

        return response($html, 200, [
            'Content-Type' => 'application/vnd.ms-excel',
            'Content-Disposition' => 'attachment; filename="master-items.xls"',
        ]);
    }

    public function updateRandomData()
    {
        $data = MasterItem::get();
        foreach($data as $item)
        {
            $kode = $item->id;
            $kode = str_pad($kode, 5, '0', STR_PAD_LEFT);

            $item->harga_beli = rand(100,1000000);
            $item->laba = rand(10,99);
            $item->kode = $kode;
            $item->supplier = $this->getRandomSupplier();
            $item->jenis = $this->getRandomJenis();
            $item->save();
        }
    }

    private function getRandomSupplier()
    {
        $array = ['Tokopaedi','Bukulapuk','TokoBagas','E Commurz','Blublu'];
        $random = rand(0,4);
        return $array[$random];
    }

    private function getRandomJenis()
    {
        $array = ['Obat','Alkes','Matkes','Umum','ATK'];
        $random = rand(0,4);
        return $array[$random];
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class KategoriController extends Controller
{
   public function index()
    {
        return view('kategori.index.index');
    }

    public function search(Request $request)
    {
        $kode = $request->kode;
        $nama = $request->nama;

        $data_search = Kategori::query();

        if (!empty($nama)) $data_search = $data_search->where('nama', 'LIKE', '%' . $nama . '%');
        if (!empty($kode)) $data_search = $data_search->where('kode', $kode);


        $data_search = $data_search->select('id', 'kode', 'nama')->orderBy('id')->get();


        return json_encode([
            'status' => 200,
            'data' => $data_search
        ]);
    }

    public function formView($method, $id = 0)
    {
        if ($method == 'new') {
            $item = [];
        } else {
            $item = Kategori::find($id);
        }
        $data['item'] = $item;
        $data['method'] = $method;
        return view('kategori.form.index', $data);
    }

    public function singleView($kode)
    {
        $data['data'] = Kategori::with('masterItems')->where('kode', $kode)->first();
        $data['items'] = $data['data']->masterItems;
        return view('kategori.single.index', $data);
    }

    public function exportPdf($kode)
    {
        $data['data'] = Kategori::with('masterItems')->where('kode', $kode)->firstOrFail();
        $data['items'] = $data['data']->masterItems;

        $pdf = Pdf::loadView('kategori.single.pdf', $data);
        return $pdf->download('kategori-' . $data['data']->kode . '.pdf');
    }

    public function formSubmit(Request $request, $method, $id = 0)
    {
        if ($method == 'new') {
            $data_item = new Kategori;
        } else {
            $data_item = Kategori::find($id);
        }

        $data_item->kode = $request->kode;
        $data_item->nama = $request->nama;
        $data_item->save();

        return redirect('kategori');
    }

    public function delete($id)
    {
        Kategori::find($id)->delete();
        return redirect('kategori');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bagian;
use RealRashid\SweetAlert\Facades\Alert;

class BagianController extends Controller
{
    public function index()
    {
        $bagians = Bagian::all();
        $title = 'Konfirmasi Hapus Bagian?';
        $text = 'Data akan dihapus dan tidak dapat dikembalikan, lanjutkan`';
        confirmDelete($title, $text);
        return view('bagian.index', compact('bagians'));
    }

    public function show($id)
    {
        $bagian = Bagian::findOrFail($id);
        return view('bagian.show', compact('bagian'));
    }

public function destroy($id)
    {
        $bagian = Bagian::findOrFail($id);  
        $bagian->delete();
        Alert::success('Success', 'Data bagian berhasil dihapus.');
        return redirect()->route('bagian.index');
    }

}

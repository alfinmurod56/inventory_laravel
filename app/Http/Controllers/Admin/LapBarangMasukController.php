<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\BarangmasukModel;
use App\Models\Admin\WebModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Barryvdh\DomPDF\Facade\Pdf;

class LapBarangMasukController extends Controller
{
    public function index(Request $request)
    {
        $data["title"] = "Lap Barang Masuk";
        return view('Admin.Laporan.BarangMasuk.index', $data);
    }

    public function print(Request $request)
    {
        if ($request->tglawal) {
            $data['data'] = BarangmasukModel::leftJoin('tbl_barang', 'tbl_barang.barang_id', '=', 'tbl_barangmasuk.barang_id')->leftJoin('tbl_supplier', 'tbl_supplier.supplier_id', '=', 'tbl_barangmasuk.supplier_id')->whereBetween('bm_tanggal', [$request->tglawal, $request->tglakhir])->orderBy('bm_id', 'DESC')->get();
        } else {
            $data['data'] = BarangmasukModel::leftJoin('tbl_barang', 'tbl_barang.barang_id', '=', 'tbl_barangmasuk.barang_id')->leftJoin('tbl_supplier', 'tbl_supplier.supplier_id', '=', 'tbl_barangmasuk.supplier_id')->orderBy('bm_id', 'DESC')->get();
        }

        $data["title"] = "Print Barang Masuk";
        $data['web'] = WebModel::first();
        $data['tglawal'] = $request->tglawal;
        $data['tglakhir'] = $request->tglakhir;
        return view('Admin.Laporan.BarangMasuk.print', $data);
    }

    public function pdf(Request $request)
    {
        if ($request->tglawal) {
            $data['data'] = BarangmasukModel::leftJoin('tbl_barang', 'tbl_barang.barang_id', '=', 'tbl_barangmasuk.barang_id')->leftJoin('tbl_supplier', 'tbl_supplier.supplier_id', '=', 'tbl_barangmasuk.supplier_id')->whereBetween('bm_tanggal', [$request->tglawal, $request->tglakhir])->orderBy('bm_id', 'DESC')->get();
        } else {
            $data['data'] = BarangmasukModel::leftJoin('tbl_barang', 'tbl_barang.barang_id', '=', 'tbl_barangmasuk.barang_id')->leftJoin('tbl_supplier', 'tbl_supplier.supplier_id', '=', 'tbl_barangmasuk.supplier_id')->orderBy('bm_id', 'DESC')->get();
        }

        $data["title"] = "PDF Barang Masuk";
        $data['web'] = WebModel::first();
        $data['tglawal'] = $request->tglawal;
        $data['tglakhir'] = $request->tglakhir;
        $pdf = Pdf::loadView('Admin.Laporan.BarangMasuk.pdf', $data);
        
        if($request->tglawal){
            return $pdf->download('lap-bm-'.$request->tglawal.'-'.$request->tglakhir.'.pdf');
        }else{
            return $pdf->download('lap-bm-semua-tanggal.pdf');
        }
        
    }

    public function show(Request $request)
    {
        if ($request->ajax()) {
            if ($request->tglawal == '') {
                $data = BarangmasukModel::leftJoin('tbl_barang', 'tbl_barang.barang_id', '=', 'tbl_barangmasuk.barang_id')->leftJoin('tbl_supplier', 'tbl_supplier.supplier_id', '=', 'tbl_barangmasuk.supplier_id')->orderBy('bm_id', 'DESC')->get();
            } else {
                $data = BarangmasukModel::leftJoin('tbl_barang', 'tbl_barang.barang_id', '=', 'tbl_barangmasuk.barang_id')->leftJoin('tbl_supplier', 'tbl_supplier.supplier_id', '=', 'tbl_barangmasuk.supplier_id')->whereBetween('bm_tanggal', [$request->tglawal, $request->tglakhir])->orderBy('bm_id', 'DESC')->get();
            }
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('tgl', function ($row) {
                    $tgl = $row->bm_tanggal == '' ? '-' : Carbon::parse($row->bm_tanggal)->translatedFormat('d F Y');

                    return $tgl;
                })
                ->addColumn('supplier', function ($row) {
                    $supplier = $row->supplier_id == '' ? '-' : $row->supplier_nama;

                    return $supplier;
                })
                ->addColumn('barang', function ($row) {
                    $barang = $row->barang_id == '' ? '-' : $row->barang_nama;

                    return $barang;
                })
                ->rawColumns(['tgl', 'supplier', 'barang'])->make(true);
        }
    }
}
<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\Pembelian;

class Pembelians extends ResourceController
{
    public function index()
    {
        $model = new Pembelian();
        $data = model->$model->finedAll();
        return $this->respond($data);
    }

    public function insertdata()
    {
        $model = new Pembelian();
        $model->db->transBegin();
        try{
            $model->query('SET FOREIGN_KEY_CHECKS=0');
            $model->query('INSERT IGNORE INTO t_pembelian(no_transaksi,kd_divisi,kd_jenis,kd_kas,tanggal,tanggal_jatuh_tempo,diskon1,diskon2,diskon3,diskon4,pajak,keterangan,kd_user,user_add,tanggal_server,date_add,date_modif,user_modif,divisi_id) SELECT no_transaksi,kd_divisi,kd_jenis,kd_kas,tanggal,tanggal_jatuh_tempo,diskon1,diskon2,diskon3,diskon4,pajak,keterangan,kd_user,tanggal_server,date_add,user_add,date_modif,user_modif,divisi_id FROM t_penjualan WHERE tanggal < now() AND tanggal >= date_add(now(),INTERVAL -7 day)');
            $model->query('SET FOREIGN_KEY_CHECKS=0');
            $model->query('INSERT IGNORE INTO t_pembelian_detail(no_transaksi,kd_barang,kd_satuan,jenis,diskon1,diskon2,diskon3,diskon4,harga_beli,qty,point1,date_add,user_add,date_modif,user_modif,divisi_id) SELECT t_penjualan_detail.no_transaksi,t_penjualan_detail.kd_barang,t_penjualan_detail.kd_satuan,t_penjualan_detail.jenis,t_penjualan_detail.diskon1,t_penjualan_detail.diskon2,t_penjualan_detail.diskon3,t_penjualan_detail.diskon4,t_penjualan_detail.harga_jual,t_penjualan_detail.qty,t_penjualan_detail.point1,t_penjualan_detail.date_add,t_penjualan_detail.user_add,t_penjualan_detail.date_modif,t_penjualan_detail.user_modif,t_penjualan_detail.divisi_id FROM t_penjualan_detail INNER JOIN t_penjualan ON t_penjualan_detail.no_transaksi = t_penjualan.no_transaksi WHERE tanggal < now() AND tanggal >= date_add(now(),INTERVAL -7 day)');
            $response = [
                'status'   => 201,
                'error'    => null,
                'messages' => [
                    'success' => 'Data created successfully'
                ]
            ];
            $model->db->transCommit();
            return $this->respondCreated($response);
        }
        catch(\Exception $e){
            $model->db->transRollback();
            return $e->getMessage();
        }
    }
}

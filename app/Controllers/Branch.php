<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use Config\Database;

class Branch extends Controller
{
    public function dashboard()
    {
        $db = Database::connect();
        $branchId = session()->get('branch_id');

        // Fetch inventory
        $inventory = $db->table('branch_inventory')
                        ->where('branch_id', $branchId)
                        ->get()
                        ->getResultArray();


        return view('branch/dashboard', ['inventory' => $inventory]);
    }

    public function monitorInventory()
    {
        $db = Database::connect();
        $branchId = session()->get('branch_id');

        $inventory = $db->table('branch_inventory')
                        ->where('branch_id', $branchId)
                        ->get()
                        ->getResultArray();

        return view('branch/monitor_inventory', ['inventory' => $inventory]);
    }

    public function purchaseRequest()
    {
        helper(['form']);
        if ($this->request->getMethod() === 'post') {
            $db = Database::connect();
            $db->table('purchase_requests')->insert([
                'branch_id'  => session()->get('branch_id'),
                'item_name'  => $this->request->getPost('item_name'),
                'quantity'   => $this->request->getPost('quantity'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
            return redirect()->back()->with('success', 'Request submitted.');
        }
        return view('branch/purchase_request');
    }

    public function approveTransfers()
    {
        $db = Database::connect();
        $branchId = session()->get('branch_id');
        $transfers = $db->table('transfers')->where('to_branch', $branchId)->get()->getResultArray();

        return view('branch/approve_transfers', ['transfers' => $transfers]);
    }

    public function approveTransferAction($id)
    {
        $db = Database::connect();
        $db->table('transfers')->where('id', $id)->update(['status' => 'approved']);
        return redirect()->back()->with('success', 'Transfer Approved.');
    }
}

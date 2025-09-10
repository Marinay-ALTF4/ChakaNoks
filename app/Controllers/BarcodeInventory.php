<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\StockModel;

class Inventory extends BaseController
{
    public function index()
    {
        $stockModel = new StockModel();
        $data['stocks'] = $stockModel->findAll();

        return view('admin/inventory', $data);
    }

    public function create()
    {
        return view('admin/create_stock');
    }

    public function store()
    {
        $stockModel = new StockModel();

        $stockModel->save([
            'barcode'  => $this->request->getPost('barcode'),
            'name'     => $this->request->getPost('name'),
            'type'     => $this->request->getPost('type'),
            'quantity' => $this->request->getPost('quantity'),
        ]);

        return redirect()->to(base_url('admin/inventory'))->with('message', 'Item added successfully');
    }

    public function edit($id)
    {
        $stockModel = new StockModel();
        $data['item'] = $stockModel->find($id);

        return view('admin/edit_stock', $data);
    }

    public function update($id)
    {
        $stockModel = new StockModel();

        $stockModel->update($id, [
            'barcode'  => $this->request->getPost('barcode'),
            'name'     => $this->request->getPost('name'),
            'type'     => $this->request->getPost('type'),
            'quantity' => $this->request->getPost('quantity'),
        ]);

        return redirect()->to(base_url('admin/inventory'))->with('message', 'Item updated successfully');
    }

    public function delete($id)
    {
        $stockModel = new StockModel();
        $stockModel->delete($id);

        return redirect()->to(base_url('admin/inventory'))->with('message', 'Item deleted successfully');
    }
}

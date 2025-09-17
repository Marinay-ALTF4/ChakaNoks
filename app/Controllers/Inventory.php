<?php  

namespace App\Controllers;  
use App\Controllers\BaseController;  
use App\Models\InventoryModel;  

class Inventory extends BaseController  
{  
    // 📊 Dashboard  
    public function dashboard()  
    {  
        $inventoryModel = new InventoryModel();  

        $data['stockCount']  = $inventoryModel->countAll();  
        $data['lowStock']    = $inventoryModel->where('quantity <', 5)->countAllResults();  
        $data['recentItems'] = $inventoryModel->orderBy('updated_at', 'DESC')->findAll(5);  

        return view('inventory/dashboard', $data);  
    }  

    // ➕ Add stock  
    public function addStock()  
    {  
        $inventoryModel = new InventoryModel();  

        if ($this->request->getMethod() === 'post') {  
            $inventoryModel->save([  
                'item_name' => $this->request->getPost('item_name'),  
                'quantity'  => $this->request->getPost('quantity'),  
                'status'    => 'available',  
                'updated_at'=> date('Y-m-d H:i:s')  
            ]);  
            return redirect()->to(site_url('inventory/stock-list'));  
        }  

        return view('inventory/add_stock');  
    }  

    // ✏️ Edit stock  
    public function editStock()  
    {  
        $inventoryModel = new InventoryModel();  

        if ($this->request->getMethod() === 'post') {  
            $id       = $this->request->getPost('id');  
            $quantity = $this->request->getPost('quantity');  

            $inventoryModel->update($id, [  
                'quantity'   => $quantity,  
                'updated_at' => date('Y-m-d H:i:s')  
            ]);  
            return redirect()->to(site_url('inventory/stock-list'));  
        }  

        $data['items'] = $inventoryModel->findAll();  
        return view('inventory/edit_stock', $data);  
    }  

    // 📋 Stock list  
    public function stockList()  
    {  
        $inventoryModel = new InventoryModel();  
        $data['items'] = $inventoryModel->findAll();  
        return view('inventory/stock_list', $data);  
    }  

    // ⚠️ Alerts  
    public function alerts()  
    {  
        $inventoryModel = new InventoryModel();  
        $data['items'] = $inventoryModel->where('quantity <', 5)  
                                        ->orWhere('status', 'damaged')  
                                        ->findAll();  
        return view('inventory/alerts', $data);  
    }  

    // 🔄 Update stock levels (when items are used/sold)  
    public function updateStock()  
    {  
        $inventoryModel = new InventoryModel();  

        if ($this->request->getMethod() === 'post') {  
            $id       = $this->request->getPost('id');  
            $quantity = $this->request->getPost('quantity');  

            $inventoryModel->update($id, [  
                'quantity'   => $quantity,  
                'updated_at' => date('Y-m-d H:i:s')  
            ]);  
            return redirect()->to(site_url('inventory'))->with('success', 'Stock updated!');  
        }  

        $data['items'] = $inventoryModel->findAll();  
        return view('inventory/update_stock', $data);  
    }  

    // 📦 Receive deliveries (new items come in)  
    public function receive_delivery()
  
    {  
        $inventoryModel = new InventoryModel();  

        if ($this->request->getMethod() === 'post') {  
            $inventoryModel->save([  
                'item_name' => $this->request->getPost('item_name'),  
                'quantity'  => $this->request->getPost('quantity'),  
                'status'    => 'available',  
                'updated_at'=> date('Y-m-d H:i:s')  
            ]);  
            return redirect()->to(site_url('inventory'))->with('success', 'Delivery added!');  
        }  

        return view('inventory/receive_delivery');  
    }  

    // 🛑 Report damaged/expired goods  
    public function report_damage()  
    {  
        $inventoryModel = new InventoryModel();  

        if ($this->request->getMethod() === 'post') {  
            $id     = $this->request->getPost('id');  
            $status = $this->request->getPost('status') ?? 'damaged';  

            $inventoryModel->update($id, [  
                'status'     => $status,  
                'updated_at' => date('Y-m-d H:i:s')  
            ]);  
            return redirect()->to(site_url('inventory'))->with('success', 'Item marked damaged!');  
        }  

        $data['items'] = $inventoryModel->findAll();  
        return view('inventory/report_damage', $data);  
    }  
}  

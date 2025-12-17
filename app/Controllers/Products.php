<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use Config\Database;

class Products extends Controller
{
    protected $db;

    public function __construct()
    {
        $this->db = Database::connect();
        helper(['form', 'url']);
    }

    public function index()
    {
        if (!session()->get('logged_in') || !in_array(session()->get('role'), ['admin', 'inventory'])) {
            return redirect()->to('/');
        }

        $data = [
            'title' => 'Our Products',
            'products' => $this->getProducts(),
            'branches' => $this->db->table('branches')->get()->getResultArray()
        ];

        return view('products/index', $data);
    }

    public function create()
    {
        if (!session()->get('logged_in') || !in_array(session()->get('role'), ['admin', 'inventory'])) {
            return redirect()->to('/');
        }

        $data = [
            'title' => 'Add New Product',
            'branches' => $this->db->table('branches')->get()->getResultArray(),
            'categories' => $this->getCategories()
        ];

        return view('products/create', $data);
    }

    public function store()
    {
        if (!session()->get('logged_in') || !in_array(session()->get('role'), ['admin', 'inventory'])) {
            return redirect()->to('/');
        }

        $rules = [
            'product_name' => 'required|min_length[3]|max_length[100]',
            'category' => 'required|max_length[50]',
            'price' => 'required|numeric|greater_than[0]',
            'stock' => 'required|integer|greater_than_equal_to[0]',
            'description' => 'permit_empty|string',
            'branch_id' => 'permit_empty|integer'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'product_name' => $this->request->getPost('product_name'),
            'category' => $this->request->getPost('category'),
            'price' => $this->request->getPost('price'),
            'stock' => $this->request->getPost('stock'),
            'description' => $this->request->getPost('description'),
            'branch_id' => $this->request->getPost('branch_id') ?: null,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        $this->db->table('products')->insert($data);
        return redirect()->to('/products')->with('success', 'Product added successfully.');
    }

    public function edit($id)
    {
        if (!session()->get('logged_in') || !in_array(session()->get('role'), ['admin', 'inventory'])) {
            return redirect()->to('/');
        }

        $product = $this->db->table('products')->where('id', $id)->get()->getRowArray();
        
        if (!$product) {
            return redirect()->to('/products')->with('error', 'Product not found.');
        }

        $data = [
            'title' => 'Edit Product',
            'product' => $product,
            'branches' => $this->db->table('branches')->get()->getResultArray(),
            'categories' => $this->getCategories()
        ];

        return view('products/edit', $data);
    }

    public function update($id)
    {
        if (!session()->get('logged_in') || !in_array(session()->get('role'), ['admin', 'inventory'])) {
            return redirect()->to('/');
        }

        $rules = [
            'product_name' => 'required|min_length[3]|max_length[100]',
            'category' => 'required|max_length[50]',
            'price' => 'required|numeric|greater_than[0]',
            'stock' => 'required|integer|greater_than_equal_to[0]',
            'description' => 'permit_empty|string',
            'branch_id' => 'permit_empty|integer'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'product_name' => $this->request->getPost('product_name'),
            'category' => $this->request->getPost('category'),
            'price' => $this->request->getPost('price'),
            'stock' => $this->request->getPost('stock'),
            'description' => $this->request->getPost('description'),
            'branch_id' => $this->request->getPost('branch_id') ?: null,
            'updated_at' => date('Y-m-d H:i:s')
        ];

        $this->db->table('products')->where('id', $id)->update($data);
        return redirect()->to('/products')->with('success', 'Product updated successfully.');
    }

    public function delete($id)
    {
        if (!session()->get('logged_in') || !in_array(session()->get('role'), ['admin', 'inventory'])) {
            return redirect()->to('/');
        }

        $this->db->table('products')->where('id', $id)->delete();
        return redirect()->to('/products')->with('success', 'Product deleted successfully.');
    }

    private function getProducts()
    {
        $builder = $this->db->table('products p')
            ->select('p.*, b.name as branch_name')
            ->join('branches b', 'b.id = p.branch_id', 'left')
            ->orderBy('p.product_name', 'ASC');

        // If user is not admin, show only products for their branch or products available in all branches
        if (session()->get('role') === 'branch_manager') {
            $builder->where('p.branch_id', session()->get('branch_id'))
                   ->orWhere('p.branch_id IS NULL');
        }

        return $builder->get()->getResultArray();
    }

    private function getCategories()
    {
        // Get distinct categories from existing products
        $categories = $this->db->table('products')
            ->select('DISTINCT(category) as category')
            ->where('category IS NOT NULL')
            ->orderBy('category', 'ASC')
            ->get()
            ->getResultArray();

        $categoryList = array_column($categories, 'category');
        
        // Add some default categories if none exist yet
        if (empty($categoryList)) {
            $categoryList = [
                'Electronics',
                'Accessories',
                'Computers',
                'Audio',
                'Networking',
                'Storage',
                'Peripherals'
            ];
        }
        
        return $categoryList;
    }
}

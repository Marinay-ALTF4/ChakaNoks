<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\FranchiseModel;
use App\Models\FranchiseSupplyAllocationModel;
use App\Models\LogModel;

class FranchiseController extends Controller
{
    protected $franchiseModel;
    protected $allocationModel;
    protected $logModel;

    public function __construct()
    {
        $this->franchiseModel = new FranchiseModel();
        $this->allocationModel = new FranchiseSupplyAllocationModel();
        $this->logModel = new LogModel();
    }

    // Dashboard
    public function dashboard()
    {
        if (!session()->get('logged_in') || session()->get('role') !== 'franchise_manager') {
            return redirect()->to('/');
        }

        $data['totalFranchises'] = $this->franchiseModel->countAll();
        $data['activeFranchises'] = $this->franchiseModel->where('status', 'active')->countAllResults();
        $data['pendingApplications'] = $this->franchiseModel->where('status', 'pending')->countAllResults();

        return view('franchise/dashboard', $data);
    }

    // List All Franchises
    public function index()
    {
        $data['franchises'] = $this->franchiseModel->findAll();
        return view('franchise/index', $data);
    }

    // View Franchise Applications
    public function applications()
    {
        $data['applications'] = $this->franchiseModel->getPendingApplications();
        return view('franchise/applications', $data);
    }

    // Approve Franchise Application
    public function approveApplication($franchiseId)
    {
        $this->franchiseModel->approveFranchise($franchiseId);

        // Log the action
        $this->logModel->logAction(session()->get('user_id'), 'approved_franchise', "Approved franchise application #$franchiseId");

        return redirect()->to('/franchise/applications')->with('success', 'Franchise approved successfully');
    }

    // Reject Franchise Application
    public function rejectApplication($franchiseId)
    {
        $this->franchiseModel->rejectFranchise($franchiseId);

        // Log the action
        $this->logModel->logAction(session()->get('user_id'), 'rejected_franchise', "Rejected franchise application #$franchiseId");

        return redirect()->to('/franchise/applications')->with('success', 'Franchise rejected');
    }

    // Allocate Supply to Franchise
    public function allocateSupply($franchiseId)
    {
        if ($this->request->getMethod() === 'post') {
            $itemName = $this->request->getPost('item_name');
            $quantity = $this->request->getPost('quantity');
            $period = $this->request->getPost('period');

            $this->franchiseModel->allocateSupply($franchiseId, $itemName, $quantity, $period);

            // Log the action
            $this->logModel->logAction(session()->get('user_id'), 'allocated_supply', "Allocated $quantity of $itemName to franchise #$franchiseId");

            return redirect()->to("/franchise/view/$franchiseId")->with('success', 'Supply allocated successfully');
        }

        $data['franchise'] = $this->franchiseModel->find($franchiseId);
        return view('franchise/allocate_supply', $data);
    }

    // View Franchise Details
    public function view($franchiseId)
    {
        $data['franchise'] = $this->franchiseModel->find($franchiseId);
        $data['allocations'] = $this->allocationModel->getAllocationsByFranchise($franchiseId);
        return view('franchise/view', $data);
    }

    // Calculate Royalty
    public function calculateRoyalty($franchiseId)
    {
        if ($this->request->getMethod() === 'post') {
            $salesAmount = $this->request->getPost('sales_amount');
            $royalty = $this->franchiseModel->calculateRoyalty($franchiseId, $salesAmount);

            $data['royalty'] = $royalty;
            $data['salesAmount'] = $salesAmount;
            $data['franchise'] = $this->franchiseModel->find($franchiseId);
            return view('franchise/royalty_result', $data);
        }

        $data['franchise'] = $this->franchiseModel->find($franchiseId);
        return view('franchise/calculate_royalty', $data);
    }

    // Supply Allocation Report
    public function allocationReport()
    {
        $db = \Config\Database::connect();
        $data['allocations'] = $db->table('franchise_supply_allocations')
            ->select('franchise_supply_allocations.*, franchises.franchise_name')
            ->join('franchises', 'franchises.id = franchise_supply_allocations.franchise_id', 'left')
            ->orderBy('franchise_supply_allocations.created_at', 'DESC')
            ->get()
            ->getResultArray();
        return view('franchise/allocation_report', $data);
    }
}

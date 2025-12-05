<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;

class RoleFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();
        if (! $session->get('logged_in')) {
            return redirect()->to('/login')->with('error', 'Please log in to continue.');
        }

        $allowedRoles = $arguments ?? [];
        $currentRole  = $session->get('role');

        if (! empty($allowedRoles) && ! in_array($currentRole, $allowedRoles, true)) {
            if ($request instanceof IncomingRequest && $request->is('json')) {
                return Services::response()
                    ->setStatusCode(403)
                    ->setJSON([
                        'status'  => 'error',
                        'message' => 'You are not allowed to access this resource.',
                    ]);
            }

            return redirect()->back()->with('error', 'Unauthorized for this module.');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No-op
    }
}

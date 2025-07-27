<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    /**
     * Do whatever processing this filter needs to do.
     * By default it should not return anything during
     * normal execution. However, when an abnormal state
     * is found, it should return an instance of
     * CodeIgniter\HTTP\Response. If it does, script
     * execution will end and that Response will be
     * sent back to the client, allowing for error pages,
     * redirects, etc.
     *
     * @param RequestInterface $request
     * @param array|null       $arguments
     *
     * @return RequestInterface|ResponseInterface|string|void
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();
        $uri = $request->getUri();
        $path = $uri->getPath();
        
        // Skip authentication check for login routes and test area
        if (strpos($path, '/admin/login') !== false || strpos($path, '/settings/test-area') !== false) {
            return;
        }
        
        // Check if user is logged in
        if (!$session->get('is_logged_in')) {
            // Store the intended URL for redirect after login
            $session->set('redirect_url', current_url());
            
            // Redirect to login page
            return redirect()->to('/admin/login')->with('error', 'Você precisa fazer login para acessar esta página.');
        }
        
        // Check role-based access if arguments are provided
        if ($arguments && is_array($arguments)) {
            $userRole = $session->get('user_role');
            
            if (!in_array($userRole, $arguments)) {
                return redirect()->to('/admin/dashboard')->with('error', 'Você não tem permissão para acessar esta página.');
            }
        }
    }

    /**
     * Allows After filters to inspect and modify the response
     * object as needed. This method does not allow any way
     * to stop execution of other after filters, short of
     * throwing an Exception or Error.
     *
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     * @param array|null        $arguments
     *
     * @return ResponseInterface|void
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No processing needed after request
    }
}

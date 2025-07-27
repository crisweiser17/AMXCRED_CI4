<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;
use CodeIgniter\HTTP\ResponseInterface;

class AuthController extends BaseController
{
    protected $userModel;
    protected $session;
    
    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->session = session();
    }
    
    /**
     * Show login form
     */
    public function login()
    {
        // If already logged in, redirect to dashboard
        if ($this->isLoggedIn()) {
            return redirect()->to('/admin/dashboard');
        }
        
        return view('auth/login');
    }
    
    /**
     * Process login
     */
    public function processLogin()
    {
        $rules = [
            'email' => 'required|valid_email',
            'password' => 'required'
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        
        $user = $this->userModel->verifyCredentials($email, $password);
        
        if ($user) {
            // Set session data
            $sessionData = [
                'user_id' => $user['id'],
                'user_name' => $user['name'],
                'user_email' => $user['email'],
                'user_role' => $user['role'],
                'is_logged_in' => true
            ];
            
            $this->session->set($sessionData);
            
            return redirect()->to('/admin/dashboard')->with('success', 'Login realizado com sucesso!');
        } else {
            return redirect()->back()->withInput()->with('error', 'Email ou senha inválidos.');
        }
    }
    
    /**
     * Logout
     */
    public function logout()
    {
        $this->session->destroy();
        return redirect()->to('/admin/login')->with('success', 'Logout realizado com sucesso!');
    }
    
    /**
     * Dashboard
     */
    public function dashboard()
    {
        if (!$this->isLoggedIn()) {
            return redirect()->to('/admin/login');
        }
        
        // Get complete user data from database
        $userId = $this->session->get('user_id');
        $user = $this->userModel->find($userId);
        
        $data = [
            'title' => 'Dashboard Administrativo',
            'user' => $user
        ];
        
        return view('auth/dashboard', $data);
    }
    
    /**
     * Check if user is logged in
     */
    public function isLoggedIn(): bool
    {
        return $this->session->get('is_logged_in') === true;
    }
    
    /**
     * Get current user data
     */
    public function getCurrentUser(): ?array
    {
        if (!$this->isLoggedIn()) {
            return null;
        }
        
        return [
            'id' => $this->session->get('user_id'),
            'name' => $this->session->get('user_name'),
            'email' => $this->session->get('user_email'),
            'role' => $this->session->get('user_role')
        ];
    }
    
    /**
     * Check if user has specific role
     */
    public function hasRole(string $role): bool
    {
        return $this->session->get('user_role') === $role;
    }
    
    /**
     * Check if user has any of the specified roles
     */
    public function hasAnyRole(array $roles): bool
    {
        $userRole = $this->session->get('user_role');
        return in_array($userRole, $roles);
    }
}

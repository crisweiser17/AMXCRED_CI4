<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateDefaultAdminUser extends Migration
{
    public function up()
    {
        // Create default admin user
        $data = [
            'name' => 'Administrador',
            'email' => 'admin@amxcred.com',
            'password' => password_hash('admin123', PASSWORD_DEFAULT),
            'role' => 'admin',
            'status' => 'active',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        $this->db->table('users')->insert($data);
    }

    public function down()
    {
        // Remove default admin user
        $this->db->table('users')->where('email', 'admin@amxcred.com')->delete();
    }
}

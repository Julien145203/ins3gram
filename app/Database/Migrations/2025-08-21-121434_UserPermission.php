<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UserPermission extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'unique' => true,
                'null' => false
            ],
            'slug' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'unique' => true,
            ]
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('user_permission');
    }

    public function down() {
        if ($this->db->tableExists('user_permission')) $this->forge->dropTable('user_permission');
    }
}

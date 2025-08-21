<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Brand extends Migration
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
                'unique'     => true,
                'null' => false
            ]
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('brand');
    }

    public function down()
    {
        $this->forge->dropTable('brand');
    }
}

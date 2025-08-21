<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Tag extends Migration
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
                'null' => false,
                'unique' => true,
            ]
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('tag');
    }

    public function down()
    {
        $this->forge->dropTable('tag');
    }
}

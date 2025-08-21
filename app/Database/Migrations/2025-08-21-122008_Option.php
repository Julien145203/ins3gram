<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Option extends Migration
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
            'key' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'unique' => true,
            ],
            'value' => [
                'type'       => 'TEXT'
            ]
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('option');
    }

    public function down()
    {
       $this->forge->dropTable('option');
    }
}

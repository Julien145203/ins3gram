<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CategIng extends Migration
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
            'id_categ_parent' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'null' => true
            ]
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('id_categ_parent', 'categ_ing','id', 'CASCADE', 'SET NULL');
        $this->forge->createTable('categ_ing');
    }

    public function down()
    {
        $this->forge->dropTable('categ_ing');
    }
}

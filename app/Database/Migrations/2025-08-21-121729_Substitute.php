<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Substitute extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_ingredient_base' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
            ],
            'id_ingredient_sub' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
            ]
        ]);

        $this->forge->addKey('id_ingredient_base', true);
        $this->forge->addKey('id_ingredient_sub', true);
        $this->forge->addForeignKey('id_ingredient_base', 'ingredient','id', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('id_ingredient_sub', 'ingredient','id', 'CASCADE', 'RESTRICT');
        $this->forge->createTable('substitute');
    }

    public function down()
    {
        $this->forge->dropTable('substitute');
    }
}

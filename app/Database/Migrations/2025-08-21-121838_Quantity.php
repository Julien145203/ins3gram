<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Quantity extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_ingredient' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'id_recipe' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'id_unit' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'quantity' => [
                'type' => 'FLOAT',
                'null' => false,
            ],
        ]);

        $this->forge->addKey(['id_ingredient', 'id_recipe', 'id_unit'], true);

        $this->forge->addForeignKey('id_ingredient', 'ingredient', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('id_recipe', 'recipe', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('id_unit', 'unit', 'id', 'CASCADE', 'RESTRICT');

        $this->forge->createTable('quantity');
    }

    public function down()
    {
        $this->forge->dropTable('quantity');
    }
}

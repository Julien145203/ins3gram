<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Favorite extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_user' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
            ],
            'id_recipe' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
            ]
        ]);

        $this->forge->addKey('id_tag', true);
        $this->forge->addKey('id_recipe', true);
        $this->forge->addForeignKey('id_user', 'user','id', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('id_recipe', 'recipe','id', 'CASCADE', 'RESTRICT');
        $this->forge->createTable('favorite');
    }

    public function down()
    {
        $this->forge->dropTable('favorite');
    }
}

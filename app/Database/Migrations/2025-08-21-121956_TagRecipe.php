<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class TagRecipe extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_recipe' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
            ],
            'id_tag' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
            ]
        ]);

        $this->forge->addKey('id_recipe', true);
        $this->forge->addKey('id_tag', true);
        $this->forge->addForeignKey('id_recipe', 'recipe','id', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('id_tag', 'tag','id', 'CASCADE', 'RESTRICT');
        $this->forge->createTable('tag_recipe');
    }

    public function down()
    {
        $this->forge->dropTable('tag_recipe');
    }
}

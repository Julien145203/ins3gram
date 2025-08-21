<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Step extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => true,
                'unsigned' => true,
            ],
            'description' => [
                'type' => 'TEXT',
                'constraint' => 11,
                'null' => false,
                'unique' => true,
            ],
            'order' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => false,
            ],
            'id_recipe'=>[
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('id_recipe', 'recipe', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->createTable('step');

    }

    public function down()
    {
        $this->forge->dropTable('step');
    }
}

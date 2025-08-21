<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Chat extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'content' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => false,
            ],
            'id_sender' => [
                'type' => 'INT',
                'unsigned' => true,
                'constraint' => 11,
                'null' => false,
            ],
            'id_receiver' => [
                'type' => 'INT',
                'unsigned' => true,
                'constraint' => 11,
                'null' => false,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('id_sender', 'user', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('id_receiver', 'user', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('chat');
    }

    public function down()
    {
        $this->forge->dropTable('chat');
    }
}

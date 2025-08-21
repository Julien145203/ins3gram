<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Recipe extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'=>[
                'type'=>'INT',
                'constraint'=>11,
                'unsigned'=>true,
                'auto_increment'=>true,
                'null'=>false
            ],
            'name'=>[
                'type'=>'VARCHAR',
                'constraint'=>'255',
                'unique'=>true,
                'null'=>false
            ],
            'alcool'=>[
                'type'=>'BOOLEAN'
            ],
            'id_user'=>[
                'type'=>'INT',
                'constraint'=>11,
                'unsigned'=>true,
                'null' => false
            ],
            'created_at'=>[
                'type'=>'DATETIME',
                'null'=>true,
            ],
            'updated_at'=>[
                'type'=>'DATETIME',
                'null'=>true,
            ],
            'deleted_at'=>[
                'type'=>'DATETIME',
                'null'=>true,
            ]
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('id_user','user','id', 'CASCADE', 'RESTRICT');
        $this->forge->createTable('recipe');
    }

    public function down()
    {
        $this->forge->dropTable('recipe');
    }
}

<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Ingredient extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'=>[
                'type'=>'INT',
                'constraint'=>11,
                'unsigned'=>true,
                'auto_increment'=>true,
            ],
            'name'=>[
                'type'=>'VARCHAR',
                'constraint'=>255,
                'null'=>false,
                'unique'=>true,
            ],
            'description'=>[
                'type'=>'TEXT',
                'null'=>true,
            ],
            'id_brand'=>[
                'type'=>'INT',
                'constraint'=>11,
                'unsigned'=>true,
                'null'=>true,
            ],
            'id_categ'=>[
                'type'=>'INT',
                'constraint'=>11,
                'unsigned'=>true,
            ],
        ]);
        $this->forge->addKey('id',true);
        $this->forge->addForeignKey('id_brand','brand','id','CASCADE','SET NULL');
        $this->forge->addForeignKey('id_categ','categ_ing','id','CASCADE','RESTRICT');
        $this->forge->createTable('ingredient');
    }

    public function down()
    {
        $this->forge->dropTable('ingredient');
    }
}

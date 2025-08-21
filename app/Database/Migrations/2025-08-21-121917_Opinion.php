<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Opinion extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'=>[
                'type'=>'INT',
                'constraint'=>11,
                'unsigned'=>true,
                'auto_increment'=>true
            ],
            'comments'=>[
                'type'=>'TEXT',
                'NULL'=>true,
            ],
            'score'=>[
                'type'=>'INT',
                'constraint'=>1, //score de 1 à 5,
                'NULL'=>false,
            ],
            'id_recipe'=>[
                'type'=>'INT',
                'constraint'=>11,
                'unsigned'=>true,
                'NULL' => false,
            ],
            'id_user'=>[
                'type'=>'INT',
                'constraint'=>11,
                'unsigned'=>true,
                'NULL' => false,
            ],
            'created_at'=>[
                'type'=>'DATETIME',
                'NULL'=>true,
            ],
            'updated_at'=>[
                'type'=>'DATETIME',
                'NULL'=>true,
            ],
            'deleted_at'=>[
                'type'=>'DATETIME',
                'NULL'=>true,
            ],
        ]);
        // clé primaire
        $this->forge->addKey('id',true);

        //clé étrangère
        $this->forge->addForeignKey('id_recipe','recipe','id','CASCADE','RESTRICT');
        $this->forge->addForeignKey('id_user','user','id','CASCADE','RESTRICT');

        //créer la table
        $this->forge->createTable('opinion');
    }

    public function down()
    {
        $this->forge->dropTable('opinion');
    }
}

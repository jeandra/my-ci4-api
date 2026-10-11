<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePostsTable extends Migration
{
    public function up()
    {
        if ($this->db->tableExists('posts')) {
            $missingFields = array_diff(['id', 'title', 'body'], $this->db->getFieldNames('posts'));
            if ($missingFields !== []) {
                throw new \RuntimeException('Tabel posts sudah ada dengan struktur yang tidak sesuai.');
            }

            return;
        }

        $this->forge->addField([
            'id'    => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'title' => ['type' => 'VARCHAR', 'constraint' => '255'],
            'body'  => ['type' => 'TEXT'],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('posts');
    }

    public function down()
    {
        //
    }
}

<?php

use Phinx\Migration\AbstractMigration;

class UpdateAuthorAndShow extends AbstractMigration {

    /**
     * Change Method.
     *
     * More information on this method is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-change-method
     *
     * Uncomment this method if you would like to use it.
     */
    public function change() {
        $table = $this->table('showauthor');
        $table->addColumn('nick', 'text', array('limit' => 100));
        $table->update();


        $this->adapter->dropColumn("author", 'definition');
        $this->adapter->dropColumn("author", 'banner');
        $this->adapter->dropColumn("author", 'description');

        $program = $this->table('program');
        $program->drop();
    }

}
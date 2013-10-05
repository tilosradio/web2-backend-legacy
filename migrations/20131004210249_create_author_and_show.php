<?php

use Phinx\Migration\AbstractMigration;

class CreateAuthorAndShow extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-change-method
     *
     * Uncomment this method if you would like to use it.
     */
    public function change()
    {

      $author = $this->table('author');
      $author->addColumn('name','text' , array('limit'=>100));
      $author->addColumn('definition','text',array('limit'=>255));
      $author->addColumn('banner','text',array('limit'=>50));
      $author->addColumn('description','text' );
      $author->create();

      $program = $this->table('radioshow');
      $program->addColumn('name','text' , array('limit'=>100));
      $program->addColumn('definition','text',array('limit'=>255));
      $program->addColumn('slug','text',array('limit'=>25));
      $program->addColumn('banner','text',array('limit'=>50));
      $program->addColumn('description','text' );
      $program->create();

      $sa = $this->table('showauthor');
      $sa->addColumn("author_id","integer");
      $sa->addColumn("radioshow_id","integer");
      $sa->addForeignKey("author_id","author","id");
      $sa->addForeignKey("radioshow_id","radioshow","id");
      $sa->create();



    }
    
    
    /**
     * Migrate Up.
     */
    public function up()
    {
    
    }

    /**
     * Migrate Down.
     */
    public function down()
    {

    }
}
<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201205183003 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA comment');
        $this->addSql('CREATE SCHEMA person');
        $this->addSql('CREATE SCHEMA post');
        $this->addSql('CREATE TABLE comment.comment (
            id UUID NOT NULL, 
            content TEXT NOT NULL,
            post_id UUID NOT NULL, 
            author_id UUID NOT NULL, 
            created_at TIMESTAMP(0) WITH TIME ZONE NOT NULL, 
            PRIMARY KEY(id))
        ');
        $this->addSql('COMMENT ON COLUMN comment.comment.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN comment.comment.author_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN comment.comment.created_at IS \'(DC2Type:datetimetz_immutable)\'');
        $this->addSql('CREATE TABLE person.person (
            id UUID NOT NULL, 
            first_name VARCHAR(40) NOT NULL, 
            last_name VARCHAR(40) DEFAULT NULL, 
            PRIMARY KEY(id))
        ');
        $this->addSql('COMMENT ON COLUMN person.person.id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE post.post (
            id UUID NOT NULL, 
            content TEXT NOT NULL, 
            owner_id UUID NOT NULL, 
            PRIMARY KEY(id))
        ');
        $this->addSql('COMMENT ON COLUMN post.post.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN post.post.owner_id IS \'(DC2Type:uuid)\'');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
//        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE comment.comment');
        $this->addSql('DROP TABLE person.person');
        $this->addSql('DROP TABLE post.post');
    }
}

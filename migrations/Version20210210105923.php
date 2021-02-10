<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210210105923 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE category (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__task AS SELECT id, task, completed, notes FROM task');
        $this->addSql('DROP TABLE task');
        $this->addSql('CREATE TABLE task (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, category_id INTEGER NOT NULL, completed BOOLEAN DEFAULT NULL, notes VARCHAR(255) DEFAULT NULL COLLATE BINARY, task VARCHAR(255) NOT NULL, CONSTRAINT FK_527EDB2512469DE2 FOREIGN KEY (category_id) REFERENCES category (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO task (id, task, completed, notes) SELECT id, task, completed, notes FROM __temp__task');
        $this->addSql('DROP TABLE __temp__task');
        $this->addSql('CREATE INDEX IDX_527EDB2512469DE2 ON task (category_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP INDEX IDX_527EDB2512469DE2');
        $this->addSql('CREATE TEMPORARY TABLE __temp__task AS SELECT id, task, completed, notes FROM task');
        $this->addSql('DROP TABLE task');
        $this->addSql('CREATE TABLE task (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, completed BOOLEAN DEFAULT NULL, notes VARCHAR(255) DEFAULT NULL, task CLOB NOT NULL COLLATE BINARY)');
        $this->addSql('INSERT INTO task (id, task, completed, notes) SELECT id, task, completed, notes FROM __temp__task');
        $this->addSql('DROP TABLE __temp__task');
    }
}

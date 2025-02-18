<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201006153600 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE label CHANGE color color VARCHAR(255) DEFAULT \'#FFFFFF\'');
        $this->addSql('ALTER TABLE note ADD created_at DATETIME NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE label CHANGE color color VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'white\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE note DROP created_at');
    }
}

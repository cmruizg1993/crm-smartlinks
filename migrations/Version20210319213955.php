<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210319213955 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE equipo ADD tipo_id INT NOT NULL');
        $this->addSql('ALTER TABLE equipo ADD CONSTRAINT FK_C49C530BA9276E6C FOREIGN KEY (tipo_id) REFERENCES tipo_equipo (id)');
        $this->addSql('CREATE INDEX IDX_C49C530BA9276E6C ON equipo (tipo_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE equipo DROP FOREIGN KEY FK_C49C530BA9276E6C');
        $this->addSql('DROP INDEX IDX_C49C530BA9276E6C ON equipo');
        $this->addSql('ALTER TABLE equipo DROP tipo_id');
    }
}

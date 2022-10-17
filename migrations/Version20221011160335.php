<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221011160335 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE equipo DROP FOREIGN KEY FK_C49C530BA9276E6C');
        $this->addSql('DROP TABLE tipo_equipo');
        $this->addSql('DROP INDEX IDX_C49C530BA9276E6C ON equipo');
        $this->addSql('ALTER TABLE equipo DROP tipo_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE tipo_equipo (id INT AUTO_INCREMENT NOT NULL, codigo VARCHAR(1) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, nombre VARCHAR(20) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE equipo ADD tipo_id INT NOT NULL');
        $this->addSql('ALTER TABLE equipo ADD CONSTRAINT FK_C49C530BA9276E6C FOREIGN KEY (tipo_id) REFERENCES tipo_equipo (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_C49C530BA9276E6C ON equipo (tipo_id)');
    }
}

<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210311173605 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE canton (id INT AUTO_INCREMENT NOT NULL, provincia_id INT NOT NULL, nombre VARCHAR(60) NOT NULL, INDEX IDX_5B9EF9214E7121AF (provincia_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE parroquia (id INT AUTO_INCREMENT NOT NULL, canton_id INT NOT NULL, nombre VARCHAR(60) NOT NULL, INDEX IDX_23A716688D070D0B (canton_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE provincia (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(60) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE canton ADD CONSTRAINT FK_5B9EF9214E7121AF FOREIGN KEY (provincia_id) REFERENCES provincia (id)');
        $this->addSql('ALTER TABLE parroquia ADD CONSTRAINT FK_23A716688D070D0B FOREIGN KEY (canton_id) REFERENCES canton (id)');
        $this->addSql('ALTER TABLE cliente ADD tipodni_id INT NOT NULL, ADD dni VARCHAR(15) NOT NULL, ADD nombres VARCHAR(100) NOT NULL, ADD apellido_p VARCHAR(50) NOT NULL, ADD apellido_m VARCHAR(50) DEFAULT NULL, ADD email VARCHAR(60) NOT NULL, ADD telefono VARCHAR(15) DEFAULT NULL');
        $this->addSql('ALTER TABLE cliente ADD CONSTRAINT FK_F41C9B25570FF73C FOREIGN KEY (tipodni_id) REFERENCES tipo_dni (id)');
        $this->addSql('CREATE INDEX IDX_F41C9B25570FF73C ON cliente (tipodni_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE parroquia DROP FOREIGN KEY FK_23A716688D070D0B');
        $this->addSql('ALTER TABLE canton DROP FOREIGN KEY FK_5B9EF9214E7121AF');
        $this->addSql('DROP TABLE canton');
        $this->addSql('DROP TABLE parroquia');
        $this->addSql('DROP TABLE provincia');
        $this->addSql('ALTER TABLE cliente DROP FOREIGN KEY FK_F41C9B25570FF73C');
        $this->addSql('DROP INDEX IDX_F41C9B25570FF73C ON cliente');
        $this->addSql('ALTER TABLE cliente DROP tipodni_id, DROP dni, DROP nombres, DROP apellido_p, DROP apellido_m, DROP email, DROP telefono');
    }
}

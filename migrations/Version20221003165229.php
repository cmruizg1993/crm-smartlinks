<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221003165229 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE empresa (id INT AUTO_INCREMENT NOT NULL, nombre_configuracion VARCHAR(255) NOT NULL, razon_social VARCHAR(255) DEFAULT NULL, nombre_comercial VARCHAR(255) DEFAULT NULL, ruc VARCHAR(13) DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, telefono VARCHAR(15) DEFAULT NULL, direccion VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE establecimiento (id INT AUTO_INCREMENT NOT NULL, codigo VARCHAR(3) NOT NULL, nombre VARCHAR(255) NOT NULL, direccion VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE punto_emision (id INT AUTO_INCREMENT NOT NULL, codigo VARCHAR(3) NOT NULL, descripcion VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE empresa');
        $this->addSql('DROP TABLE establecimiento');
        $this->addSql('DROP TABLE punto_emision');
    }
}

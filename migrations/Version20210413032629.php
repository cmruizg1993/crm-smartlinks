<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210413032629 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE banco (id INT AUTO_INCREMENT NOT NULL, codigo VARCHAR(3) NOT NULL, nombre VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE codigo (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cuenta_bancaria (id INT AUTO_INCREMENT NOT NULL, banco_id INT NOT NULL, numero VARCHAR(50) NOT NULL, tipo_cuenta VARCHAR(1) NOT NULL, cedula VARCHAR(15) DEFAULT NULL, beneficiario VARCHAR(255) NOT NULL, INDEX IDX_ECD0C9CECC04A73E (banco_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE cuenta_bancaria ADD CONSTRAINT FK_ECD0C9CECC04A73E FOREIGN KEY (banco_id) REFERENCES banco (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cuenta_bancaria DROP FOREIGN KEY FK_ECD0C9CECC04A73E');
        $this->addSql('DROP TABLE banco');
        $this->addSql('DROP TABLE codigo');
        $this->addSql('DROP TABLE cuenta_bancaria');
    }
}

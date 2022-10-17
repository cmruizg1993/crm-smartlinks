<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221014162742 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE catalogo (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(50) NOT NULL, codigo VARCHAR(10) NOT NULL, descripcion VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE catalogo_catalogo (catalogo_source INT NOT NULL, catalogo_target INT NOT NULL, INDEX IDX_5BA246A02CC636A1 (catalogo_source), INDEX IDX_5BA246A03523662E (catalogo_target), PRIMARY KEY(catalogo_source, catalogo_target)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categoria_servicio (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(50) NOT NULL, codigo VARCHAR(10) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE catalogo_catalogo ADD CONSTRAINT FK_5BA246A02CC636A1 FOREIGN KEY (catalogo_source) REFERENCES catalogo (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE catalogo_catalogo ADD CONSTRAINT FK_5BA246A03523662E FOREIGN KEY (catalogo_target) REFERENCES catalogo (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE catalogo_catalogo DROP FOREIGN KEY FK_5BA246A02CC636A1');
        $this->addSql('ALTER TABLE catalogo_catalogo DROP FOREIGN KEY FK_5BA246A03523662E');
        $this->addSql('DROP TABLE catalogo');
        $this->addSql('DROP TABLE catalogo_catalogo');
        $this->addSql('DROP TABLE categoria_servicio');
    }
}

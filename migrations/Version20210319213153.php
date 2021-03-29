<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210319213153 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE equipo (id INT AUTO_INCREMENT NOT NULL, sku VARCHAR(255) NOT NULL, nombre VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE no_seriado (id INT AUTO_INCREMENT NOT NULL, equipo_id INT NOT NULL, cantidad INT NOT NULL, INDEX IDX_F674DFAE23BFBED (equipo_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE seriado (id INT AUTO_INCREMENT NOT NULL, equipo_id INT NOT NULL, serie VARCHAR(255) NOT NULL, INDEX IDX_4860F37A23BFBED (equipo_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE no_seriado ADD CONSTRAINT FK_F674DFAE23BFBED FOREIGN KEY (equipo_id) REFERENCES equipo (id)');
        $this->addSql('ALTER TABLE seriado ADD CONSTRAINT FK_4860F37A23BFBED FOREIGN KEY (equipo_id) REFERENCES equipo (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE no_seriado DROP FOREIGN KEY FK_F674DFAE23BFBED');
        $this->addSql('ALTER TABLE seriado DROP FOREIGN KEY FK_4860F37A23BFBED');
        $this->addSql('DROP TABLE equipo');
        $this->addSql('DROP TABLE no_seriado');
        $this->addSql('DROP TABLE seriado');
    }
}

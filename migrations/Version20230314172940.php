<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230314172940 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE IF NOT EXISTS secuencial (id INT AUTO_INCREMENT NOT NULL, tipo_comprobante_id INT NOT NULL, inicio INT NOT NULL, actual INT DEFAULT NULL, descripcion VARCHAR(255) DEFAULT NULL, INDEX IDX_207842C0A9B5E49A (tipo_comprobante_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE secuencial ADD CONSTRAINT FK_207842C0A9B5E49A FOREIGN KEY (tipo_comprobante_id) REFERENCES tipo_comprobante (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE secuencial');
    }
}

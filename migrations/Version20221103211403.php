<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221103211403 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE contrato ADD estado_actual_id INT DEFAULT NULL, DROP estado');
        $this->addSql('ALTER TABLE contrato ADD CONSTRAINT FK_6669652322552C49 FOREIGN KEY (estado_actual_id) REFERENCES opcion_catalogo (id)');
        $this->addSql('CREATE INDEX IDX_6669652322552C49 ON contrato (estado_actual_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE contrato DROP FOREIGN KEY FK_6669652322552C49');
        $this->addSql('DROP INDEX IDX_6669652322552C49 ON contrato');
        $this->addSql('ALTER TABLE contrato ADD estado VARCHAR(20) DEFAULT NULL, DROP estado_actual_id');
    }
}

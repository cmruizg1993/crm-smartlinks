<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221026231844 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE estado_contrato (id INT AUTO_INCREMENT NOT NULL, estado_id INT NOT NULL, fecha DATETIME NOT NULL, observaciones LONGTEXT DEFAULT NULL, INDEX IDX_979FD6CE9F5A440B (estado_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE estado_contrato ADD CONSTRAINT FK_979FD6CE9F5A440B FOREIGN KEY (estado_id) REFERENCES opcion_catalogo (id)');
        $this->addSql('ALTER TABLE factura ADD contrato_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE factura ADD CONSTRAINT FK_F9EBA00970AE7BF1 FOREIGN KEY (contrato_id) REFERENCES contrato (id)');
        $this->addSql('CREATE INDEX IDX_F9EBA00970AE7BF1 ON factura (contrato_id)');
        $this->addSql('ALTER TABLE opcion_catalogo ADD css_class VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE estado_contrato');
        $this->addSql('ALTER TABLE factura DROP FOREIGN KEY FK_F9EBA00970AE7BF1');
        $this->addSql('DROP INDEX IDX_F9EBA00970AE7BF1 ON factura');
        $this->addSql('ALTER TABLE factura DROP contrato_id');
        $this->addSql('ALTER TABLE opcion_catalogo DROP css_class');
    }
}

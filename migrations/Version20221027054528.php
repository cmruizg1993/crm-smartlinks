<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221027054528 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE factura DROP FOREIGN KEY FK_F9EBA00925662B3A');
        $this->addSql('DROP INDEX IDX_F9EBA00925662B3A ON factura');
        $this->addSql('ALTER TABLE factura DROP comprobante_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE factura ADD comprobante_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE factura ADD CONSTRAINT FK_F9EBA00925662B3A FOREIGN KEY (comprobante_id) REFERENCES tipo_comprobante (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_F9EBA00925662B3A ON factura (comprobante_id)');
    }
}

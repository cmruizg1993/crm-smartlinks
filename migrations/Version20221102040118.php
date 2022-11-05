<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221102040118 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE empresa ADD obligado_contabilidad VARCHAR(2) NOT NULL, DROP nombre_configuracion');
        $this->addSql('ALTER TABLE factura ADD subtotal12 NUMERIC(10, 2) DEFAULT NULL, ADD subtotal0 NUMERIC(10, 2) NOT NULL, ADD descuento NUMERIC(10, 2) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE empresa ADD nombre_configuracion VARCHAR(255) NOT NULL, DROP obligado_contabilidad');
        $this->addSql('ALTER TABLE factura DROP subtotal12, DROP subtotal0, DROP descuento');
    }
}

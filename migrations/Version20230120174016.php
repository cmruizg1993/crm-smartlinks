<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230120174016 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE detalle_factura ADD precio_sin_imp NUMERIC(10, 3) DEFAULT NULL, ADD precio_con_desc NUMERIC(10, 3) DEFAULT NULL');
        $this->addSql('ALTER TABLE servicio ADD precio_sin_imp NUMERIC(10, 3) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE detalle_factura DROP precio_sin_imp, DROP precio_con_desc');
        $this->addSql('ALTER TABLE servicio DROP precio_sin_imp');
    }
}

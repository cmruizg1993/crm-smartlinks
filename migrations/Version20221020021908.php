<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221020021908 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE factura ADD tipo_comprobante VARCHAR(2) DEFAULT NULL, ADD tipo_ambiente VARCHAR(1) DEFAULT NULL, ADD codigo_numerico INT DEFAULT NULL, ADD tipo_emision VARCHAR(1) DEFAULT NULL, ADD digito_verificador INT DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE factura DROP tipo_comprobante, DROP tipo_ambiente, DROP codigo_numerico, DROP tipo_emision, DROP digito_verificador');
    }
}

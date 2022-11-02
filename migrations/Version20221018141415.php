<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221018141415 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE detalle_factura ADD producto_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE detalle_factura ADD CONSTRAINT FK_B1354EA17645698E FOREIGN KEY (producto_id) REFERENCES producto (id)');
        $this->addSql('CREATE INDEX IDX_B1354EA17645698E ON detalle_factura (producto_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE detalle_factura DROP FOREIGN KEY FK_B1354EA17645698E');
        $this->addSql('DROP INDEX IDX_B1354EA17645698E ON detalle_factura');
        $this->addSql('ALTER TABLE detalle_factura DROP producto_id');
    }
}

<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221017143345 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE detalle_factura ADD servicio_id INT DEFAULT NULL, ADD es_servicio TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE detalle_factura ADD CONSTRAINT FK_B1354EA171CAA3E7 FOREIGN KEY (servicio_id) REFERENCES servicio (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B1354EA171CAA3E7 ON detalle_factura (servicio_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE detalle_factura DROP FOREIGN KEY FK_B1354EA171CAA3E7');
        $this->addSql('DROP INDEX UNIQ_B1354EA171CAA3E7 ON detalle_factura');
        $this->addSql('ALTER TABLE detalle_factura DROP servicio_id, DROP es_servicio');
    }
}

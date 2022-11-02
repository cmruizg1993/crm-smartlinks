<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221018140929 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE producto (id INT AUTO_INCREMENT NOT NULL, codigo VARCHAR(50) NOT NULL, nombre VARCHAR(255) NOT NULL, precio NUMERIC(10, 2) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE detalle_factura DROP FOREIGN KEY FK_B1354EA1126F525E');
        $this->addSql('DROP INDEX IDX_B1354EA1126F525E ON detalle_factura');
        $this->addSql('ALTER TABLE detalle_factura ADD descripcion VARCHAR(255) NOT NULL, DROP item_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE producto');
        $this->addSql('ALTER TABLE detalle_factura ADD item_id INT NOT NULL, DROP descripcion');
        $this->addSql('ALTER TABLE detalle_factura ADD CONSTRAINT FK_B1354EA1126F525E FOREIGN KEY (item_id) REFERENCES item (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_B1354EA1126F525E ON detalle_factura (item_id)');
    }
}

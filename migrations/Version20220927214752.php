<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220927214752 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE detalle_factura (id INT AUTO_INCREMENT NOT NULL, factura_id INT NOT NULL, item_id INT NOT NULL, cantidad NUMERIC(10, 2) NOT NULL, precio NUMERIC(10, 2) NOT NULL, subtotal NUMERIC(10, 2) NOT NULL, INDEX IDX_B1354EA1F04F795F (factura_id), INDEX IDX_B1354EA1126F525E (item_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE factura (id INT AUTO_INCREMENT NOT NULL, usuario_id INT NOT NULL, cliente_id INT NOT NULL, total NUMERIC(9, 2) NOT NULL, subtotal NUMERIC(10, 3) NOT NULL, base_iva NUMERIC(10, 3) NOT NULL, iva NUMERIC(10, 3) NOT NULL, base_ice NUMERIC(10, 3) DEFAULT NULL, ice NUMERIC(10, 3) DEFAULT NULL, base_cero NUMERIC(10, 3) DEFAULT NULL, base_no_imponible NUMERIC(10, 3) DEFAULT NULL, fecha DATETIME NOT NULL, serial VARCHAR(10) NOT NULL, secuencial VARCHAR(10) NOT NULL, observaciones LONGTEXT DEFAULT NULL, referencia VARCHAR(255) DEFAULT NULL, INDEX IDX_F9EBA009DB38439E (usuario_id), INDEX IDX_F9EBA009DE734E51 (cliente_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE item (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(255) NOT NULL, descripcion LONGTEXT DEFAULT NULL, tipo VARCHAR(1) NOT NULL, impuestos LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE detalle_factura ADD CONSTRAINT FK_B1354EA1F04F795F FOREIGN KEY (factura_id) REFERENCES factura (id)');
        $this->addSql('ALTER TABLE detalle_factura ADD CONSTRAINT FK_B1354EA1126F525E FOREIGN KEY (item_id) REFERENCES item (id)');
        $this->addSql('ALTER TABLE factura ADD CONSTRAINT FK_F9EBA009DB38439E FOREIGN KEY (usuario_id) REFERENCES usuario (id)');
        $this->addSql('ALTER TABLE factura ADD CONSTRAINT FK_F9EBA009DE734E51 FOREIGN KEY (cliente_id) REFERENCES cliente (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE detalle_factura DROP FOREIGN KEY FK_B1354EA1F04F795F');
        $this->addSql('ALTER TABLE detalle_factura DROP FOREIGN KEY FK_B1354EA1126F525E');
        $this->addSql('DROP TABLE detalle_factura');
        $this->addSql('DROP TABLE factura');
        $this->addSql('DROP TABLE item');
    }
}

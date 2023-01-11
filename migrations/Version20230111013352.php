<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230111013352 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE detalle_cuenta_por_cobrar (id INT AUTO_INCREMENT NOT NULL, producto_id INT DEFAULT NULL, servicio_id INT DEFAULT NULL, cuenta_id INT NOT NULL, cantidad NUMERIC(10, 2) NOT NULL, precio NUMERIC(10, 2) NOT NULL, subtotal NUMERIC(10, 2) NOT NULL, descuento NUMERIC(10, 2) DEFAULT NULL, descripcion VARCHAR(255) NOT NULL, es_servicio TINYINT(1) NOT NULL, iva NUMERIC(10, 2) DEFAULT NULL, INDEX IDX_AB6A72747645698E (producto_id), INDEX IDX_AB6A727471CAA3E7 (servicio_id), INDEX IDX_AB6A72749AEFF118 (cuenta_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE detalle_cuenta_por_cobrar ADD CONSTRAINT FK_AB6A72747645698E FOREIGN KEY (producto_id) REFERENCES producto (id)');
        $this->addSql('ALTER TABLE detalle_cuenta_por_cobrar ADD CONSTRAINT FK_AB6A727471CAA3E7 FOREIGN KEY (servicio_id) REFERENCES servicio (id)');
        $this->addSql('ALTER TABLE detalle_cuenta_por_cobrar ADD CONSTRAINT FK_AB6A72749AEFF118 FOREIGN KEY (cuenta_id) REFERENCES cuenta_por_cobrar (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE detalle_cuenta_por_cobrar');
    }
}

<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230111051304 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE detalle_factura ADD cuota_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE detalle_factura ADD CONSTRAINT FK_B1354EA16A7CF079 FOREIGN KEY (cuota_id) REFERENCES cuota_cuenta (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B1354EA16A7CF079 ON detalle_factura (cuota_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE detalle_factura DROP FOREIGN KEY FK_B1354EA16A7CF079');
        $this->addSql('DROP INDEX UNIQ_B1354EA16A7CF079 ON detalle_factura');
        $this->addSql('ALTER TABLE detalle_factura DROP cuota_id');
    }
}

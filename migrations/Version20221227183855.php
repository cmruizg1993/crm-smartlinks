<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221227183855 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE cuenta_por_cobrar (id INT AUTO_INCREMENT NOT NULL, fecha DATETIME NOT NULL, observaciones LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE factura ADD cuenta_por_cobrar_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE factura ADD CONSTRAINT FK_F9EBA009BD207FB5 FOREIGN KEY (cuenta_por_cobrar_id) REFERENCES cuenta_por_cobrar (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F9EBA009BD207FB5 ON factura (cuenta_por_cobrar_id)');
        $this->addSql('ALTER TABLE pago ADD cuenta_por_cobrar_id INT NOT NULL');
        $this->addSql('ALTER TABLE pago ADD CONSTRAINT FK_F4DF5F3EBD207FB5 FOREIGN KEY (cuenta_por_cobrar_id) REFERENCES cuenta_por_cobrar (id)');
        $this->addSql('CREATE INDEX IDX_F4DF5F3EBD207FB5 ON pago (cuenta_por_cobrar_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE factura DROP FOREIGN KEY FK_F9EBA009BD207FB5');
        $this->addSql('ALTER TABLE pago DROP FOREIGN KEY FK_F4DF5F3EBD207FB5');
        $this->addSql('DROP TABLE cuenta_por_cobrar');
        $this->addSql('DROP INDEX UNIQ_F9EBA009BD207FB5 ON factura');
        $this->addSql('ALTER TABLE factura DROP cuenta_por_cobrar_id');
        $this->addSql('DROP INDEX IDX_F4DF5F3EBD207FB5 ON pago');
        $this->addSql('ALTER TABLE pago DROP cuenta_por_cobrar_id');
    }
}

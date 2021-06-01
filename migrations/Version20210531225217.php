<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210531225217 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE pago (id INT AUTO_INCREMENT NOT NULL, cta_bancaria_id INT NOT NULL, valor DOUBLE PRECISION NOT NULL, nro_documento VARCHAR(50) NOT NULL, comprobante VARCHAR(255) DEFAULT NULL, INDEX IDX_F4DF5F3EF31219BF (cta_bancaria_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE pago ADD CONSTRAINT FK_F4DF5F3EF31219BF FOREIGN KEY (cta_bancaria_id) REFERENCES cuenta_bancaria (id)');
        $this->addSql('ALTER TABLE solicitud ADD pago_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE solicitud ADD CONSTRAINT FK_96D27CC063FB8380 FOREIGN KEY (pago_id) REFERENCES pago (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_96D27CC063FB8380 ON solicitud (pago_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE solicitud DROP FOREIGN KEY FK_96D27CC063FB8380');
        $this->addSql('DROP TABLE pago');
        $this->addSql('DROP INDEX UNIQ_96D27CC063FB8380 ON solicitud');
        $this->addSql('ALTER TABLE solicitud DROP pago_id');
    }
}

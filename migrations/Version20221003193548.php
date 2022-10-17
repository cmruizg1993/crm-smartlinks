<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221003193548 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE punto_emision ADD tipo_comprobante_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE punto_emision ADD CONSTRAINT FK_74905531A9B5E49A FOREIGN KEY (tipo_comprobante_id) REFERENCES tipo_comprobante (id)');
        $this->addSql('CREATE INDEX IDX_74905531A9B5E49A ON punto_emision (tipo_comprobante_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE punto_emision DROP FOREIGN KEY FK_74905531A9B5E49A');
        $this->addSql('DROP INDEX IDX_74905531A9B5E49A ON punto_emision');
        $this->addSql('ALTER TABLE punto_emision DROP tipo_comprobante_id');
    }
}

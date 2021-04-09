<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210406195422 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE venta_hughes (id INT AUTO_INCREMENT NOT NULL, vendedor_id INT NOT NULL, INDEX IDX_DEBA4FAE8361A8B8 (vendedor_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE venta_hughes ADD CONSTRAINT FK_DEBA4FAE8361A8B8 FOREIGN KEY (vendedor_id) REFERENCES colaborador (id)');
        $this->addSql('ALTER TABLE cliente ADD vendedor_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE cliente ADD CONSTRAINT FK_F41C9B258361A8B8 FOREIGN KEY (vendedor_id) REFERENCES colaborador (id)');
        $this->addSql('CREATE INDEX IDX_F41C9B258361A8B8 ON cliente (vendedor_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE venta_hughes');
        $this->addSql('ALTER TABLE cliente DROP FOREIGN KEY FK_F41C9B258361A8B8');
        $this->addSql('DROP INDEX IDX_F41C9B258361A8B8 ON cliente');
        $this->addSql('ALTER TABLE cliente DROP vendedor_id');
    }
}

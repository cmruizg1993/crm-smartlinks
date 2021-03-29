<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210316215738 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE mensaje_wtp_out (id INT AUTO_INCREMENT NOT NULL, contact_id INT NOT NULL, mensaje_id INT NOT NULL, INDEX IDX_9E5A42A4E7A1254A (contact_id), INDEX IDX_9E5A42A44C54F362 (mensaje_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE mensaje_wtp_out ADD CONSTRAINT FK_9E5A42A4E7A1254A FOREIGN KEY (contact_id) REFERENCES contact_wtp (id)');
        $this->addSql('ALTER TABLE mensaje_wtp_out ADD CONSTRAINT FK_9E5A42A44C54F362 FOREIGN KEY (mensaje_id) REFERENCES mensaje (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE mensaje_wtp_out');
    }
}

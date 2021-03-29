<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210316192304 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE contact_wtp (id INT AUTO_INCREMENT NOT NULL, uid VARCHAR(18) NOT NULL, pic LONGTEXT DEFAULT NULL, type VARCHAR(10) DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE mensaje_wtp (id INT AUTO_INCREMENT NOT NULL, contact_id INT DEFAULT NULL, dtm INT DEFAULT NULL, uid VARCHAR(255) DEFAULT NULL, cuid VARCHAR(255) DEFAULT NULL, type VARCHAR(20) DEFAULT NULL, dir VARCHAR(2) DEFAULT NULL, body LONGTEXT DEFAULT NULL, url LONGTEXT DEFAULT NULL, mediakey LONGTEXT DEFAULT NULL, mimetype LONGTEXT DEFAULT NULL, INDEX IDX_7D8D561DE7A1254A (contact_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE mensaje_wtp ADD CONSTRAINT FK_7D8D561DE7A1254A FOREIGN KEY (contact_id) REFERENCES contact_wtp (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE mensaje_wtp DROP FOREIGN KEY FK_7D8D561DE7A1254A');
        $this->addSql('DROP TABLE contact_wtp');
        $this->addSql('DROP TABLE mensaje_wtp');
    }
}

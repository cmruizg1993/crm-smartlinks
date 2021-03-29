<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210319221131 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE san ADD parroquia_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE san ADD CONSTRAINT FK_7F1A19A274AFDC17 FOREIGN KEY (parroquia_id) REFERENCES parroquia (id)');
        $this->addSql('CREATE INDEX IDX_7F1A19A274AFDC17 ON san (parroquia_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE san DROP FOREIGN KEY FK_7F1A19A274AFDC17');
        $this->addSql('DROP INDEX IDX_7F1A19A274AFDC17 ON san');
        $this->addSql('ALTER TABLE san DROP parroquia_id');
    }
}

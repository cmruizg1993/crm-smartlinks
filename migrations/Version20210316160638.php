<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210316160638 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE usuario ADD colaborador_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE usuario ADD CONSTRAINT FK_2265B05DF1CB264E FOREIGN KEY (colaborador_id) REFERENCES colaborador (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2265B05DF1CB264E ON usuario (colaborador_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE usuario DROP FOREIGN KEY FK_2265B05DF1CB264E');
        $this->addSql('DROP INDEX UNIQ_2265B05DF1CB264E ON usuario');
        $this->addSql('ALTER TABLE usuario DROP colaborador_id');
    }
}

<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220304191116 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE san ADD solicitud_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE san ADD CONSTRAINT FK_7F1A19A21CB9D6E4 FOREIGN KEY (solicitud_id) REFERENCES solicitud (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_7F1A19A21CB9D6E4 ON san (solicitud_id)');
        $this->addSql('ALTER TABLE solicitud DROP FOREIGN KEY FK_96D27CC0DADF1C23');
        $this->addSql('DROP INDEX UNIQ_96D27CC0DADF1C23 ON solicitud');
        $this->addSql('ALTER TABLE solicitud DROP san_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE san DROP FOREIGN KEY FK_7F1A19A21CB9D6E4');
        $this->addSql('DROP INDEX UNIQ_7F1A19A21CB9D6E4 ON san');
        $this->addSql('ALTER TABLE san DROP solicitud_id');
        $this->addSql('ALTER TABLE solicitud ADD san_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE solicitud ADD CONSTRAINT FK_96D27CC0DADF1C23 FOREIGN KEY (san_id) REFERENCES san (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_96D27CC0DADF1C23 ON solicitud (san_id)');
    }
}

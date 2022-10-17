<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210531224338 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE solicitud ADD san_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE solicitud ADD CONSTRAINT FK_96D27CC0DADF1C23 FOREIGN KEY (san_id) REFERENCES Contrato (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_96D27CC0DADF1C23 ON solicitud (san_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE solicitud DROP FOREIGN KEY FK_96D27CC0DADF1C23');
        $this->addSql('DROP INDEX UNIQ_96D27CC0DADF1C23 ON solicitud');
        $this->addSql('ALTER TABLE solicitud DROP san_id');
    }
}

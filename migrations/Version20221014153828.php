<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221014153828 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE contrato DROP FOREIGN KEY FK_66696523E899029B');
        $this->addSql('DROP INDEX IDX_66696523E899029B ON contrato');
        $this->addSql('ALTER TABLE contrato CHANGE plan_id servicio_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE contrato ADD CONSTRAINT FK_6669652371CAA3E7 FOREIGN KEY (servicio_id) REFERENCES servicio (id)');
        $this->addSql('CREATE INDEX IDX_6669652371CAA3E7 ON contrato (servicio_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE contrato DROP FOREIGN KEY FK_6669652371CAA3E7');
        $this->addSql('DROP INDEX IDX_6669652371CAA3E7 ON contrato');
        $this->addSql('ALTER TABLE contrato CHANGE servicio_id plan_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE contrato ADD CONSTRAINT FK_66696523E899029B FOREIGN KEY (plan_id) REFERENCES servicio (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_66696523E899029B ON contrato (plan_id)');
    }
}

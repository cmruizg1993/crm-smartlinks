<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210507233616 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE solicitud_plan');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE solicitud_plan (solicitud_id INT NOT NULL, plan_id INT NOT NULL, INDEX IDX_E195105B1CB9D6E4 (solicitud_id), INDEX IDX_E195105BE899029B (plan_id), PRIMARY KEY(solicitud_id, plan_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE solicitud_plan ADD CONSTRAINT FK_E195105B1CB9D6E4 FOREIGN KEY (solicitud_id) REFERENCES solicitud (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE solicitud_plan ADD CONSTRAINT FK_E195105BE899029B FOREIGN KEY (plan_id) REFERENCES servicio (id) ON UPDATE NO ACTION ON DELETE CASCADE');
    }
}

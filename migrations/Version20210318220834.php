<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210318220834 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE contrato_plan');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_7F1A19A2F55AE19E ON san (numero)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE contrato_plan (contrato_id INT NOT NULL, plan_id INT NOT NULL, INDEX IDX_7E47450D70AE7BF1 (contrato_id), INDEX IDX_7E47450DE899029B (plan_id), PRIMARY KEY(contrato_id, plan_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE contrato_plan ADD CONSTRAINT FK_7E47450D70AE7BF1 FOREIGN KEY (contrato_id) REFERENCES contrato (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE contrato_plan ADD CONSTRAINT FK_7E47450DE899029B FOREIGN KEY (plan_id) REFERENCES plan (id) ON DELETE CASCADE');
        $this->addSql('DROP INDEX UNIQ_7F1A19A2F55AE19E ON san');
    }
}

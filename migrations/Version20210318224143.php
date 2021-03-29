<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210318224143 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE contrato_planes_claro (contrato_id INT NOT NULL, planes_claro_id INT NOT NULL, INDEX IDX_228BA9DB70AE7BF1 (contrato_id), INDEX IDX_228BA9DB5C3AE42A (planes_claro_id), PRIMARY KEY(contrato_id, planes_claro_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE orden (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tipo_orden (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(255) NOT NULL, codigo VARCHAR(1) NOT NULL, UNIQUE INDEX UNIQ_E794551F20332D99 (codigo), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE contrato_planes_claro ADD CONSTRAINT FK_228BA9DB70AE7BF1 FOREIGN KEY (contrato_id) REFERENCES contrato (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE contrato_planes_claro ADD CONSTRAINT FK_228BA9DB5C3AE42A FOREIGN KEY (planes_claro_id) REFERENCES planes_claro (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE contrato_planes_claro');
        $this->addSql('DROP TABLE orden');
        $this->addSql('DROP TABLE tipo_orden');
    }
}

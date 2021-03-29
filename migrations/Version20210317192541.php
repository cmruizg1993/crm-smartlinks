<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210317192541 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE dni (id INT AUTO_INCREMENT NOT NULL, tipo_id INT NOT NULL, numero VARCHAR(20) NOT NULL, fecha_exp DATE DEFAULT NULL, foto_frontal VARCHAR(255) DEFAULT NULL, foto_posterior VARCHAR(255) DEFAULT NULL, INDEX IDX_7F8F253BA9276E6C (tipo_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE dni ADD CONSTRAINT FK_7F8F253BA9276E6C FOREIGN KEY (tipo_id) REFERENCES tipo_dni (id)');
        $this->addSql('ALTER TABLE cliente DROP FOREIGN KEY FK_F41C9B25570FF73C');
        $this->addSql('DROP INDEX IDX_F41C9B25570FF73C ON cliente');
        $this->addSql('DROP INDEX UNIQ_F41C9B257F8F253B ON cliente');
        $this->addSql('ALTER TABLE cliente ADD dni_id INT DEFAULT NULL, DROP tipodni_id, DROP dni, CHANGE estado estado VARCHAR(1) NOT NULL');
        $this->addSql('ALTER TABLE cliente ADD CONSTRAINT FK_F41C9B25DB8B8168 FOREIGN KEY (dni_id) REFERENCES dni (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F41C9B25DB8B8168 ON cliente (dni_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cliente DROP FOREIGN KEY FK_F41C9B25DB8B8168');
        $this->addSql('DROP TABLE dni');
        $this->addSql('DROP INDEX UNIQ_F41C9B25DB8B8168 ON cliente');
        $this->addSql('ALTER TABLE cliente ADD tipodni_id INT NOT NULL, ADD dni VARCHAR(15) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, DROP dni_id, CHANGE estado estado VARCHAR(2) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE cliente ADD CONSTRAINT FK_F41C9B25570FF73C FOREIGN KEY (tipodni_id) REFERENCES tipo_dni (id)');
        $this->addSql('CREATE INDEX IDX_F41C9B25570FF73C ON cliente (tipodni_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F41C9B257F8F253B ON cliente (dni)');
    }
}

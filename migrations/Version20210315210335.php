<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210315210335 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE colaborador_proveedor (colaborador_id INT NOT NULL, proveedor_id INT NOT NULL, INDEX IDX_AC1A7AA7F1CB264E (colaborador_id), INDEX IDX_AC1A7AA7CB305D73 (proveedor_id), PRIMARY KEY(colaborador_id, proveedor_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE colaborador_proveedor ADD CONSTRAINT FK_AC1A7AA7F1CB264E FOREIGN KEY (colaborador_id) REFERENCES colaborador (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE colaborador_proveedor ADD CONSTRAINT FK_AC1A7AA7CB305D73 FOREIGN KEY (proveedor_id) REFERENCES proveedor (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE colaborador ADD parroquia_id INT DEFAULT NULL, ADD direccion VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE colaborador ADD CONSTRAINT FK_D2F80BB374AFDC17 FOREIGN KEY (parroquia_id) REFERENCES parroquia (id)');
        $this->addSql('CREATE INDEX IDX_D2F80BB374AFDC17 ON colaborador (parroquia_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE colaborador_proveedor');
        $this->addSql('ALTER TABLE colaborador DROP FOREIGN KEY FK_D2F80BB374AFDC17');
        $this->addSql('DROP INDEX IDX_D2F80BB374AFDC17 ON colaborador');
        $this->addSql('ALTER TABLE colaborador DROP parroquia_id, DROP direccion');
    }
}

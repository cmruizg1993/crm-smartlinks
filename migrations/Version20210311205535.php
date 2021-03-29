<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210311205535 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cargo ADD codigo VARCHAR(2) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_3BEE577120332D99 ON cargo (codigo)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F41C9B257F8F253B ON cliente (dni)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_16C068CE20332D99 ON proveedor (codigo)');
        $this->addSql('ALTER TABLE usuario ADD phone VARCHAR(18) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2265B05D444F97DD ON usuario (phone)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_3BEE577120332D99 ON cargo');
        $this->addSql('ALTER TABLE cargo DROP codigo');
        $this->addSql('DROP INDEX UNIQ_F41C9B257F8F253B ON cliente');
        $this->addSql('DROP INDEX UNIQ_16C068CE20332D99 ON proveedor');
        $this->addSql('DROP INDEX UNIQ_2265B05D444F97DD ON usuario');
        $this->addSql('ALTER TABLE usuario DROP phone');
    }
}

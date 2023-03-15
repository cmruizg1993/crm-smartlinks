<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230314222951 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE colaborador DROP codigo_ip, DROP ruc, DROP razon, DROP factura, DROP iva, DROP ret_fuente, DROP ret_iva');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE colaborador ADD codigo_ip INT DEFAULT NULL, ADD ruc VARCHAR(13) DEFAULT NULL, ADD razon VARCHAR(255) DEFAULT NULL, ADD factura TINYINT(1) DEFAULT NULL, ADD iva INT DEFAULT NULL, ADD ret_fuente DOUBLE PRECISION DEFAULT NULL, ADD ret_iva DOUBLE PRECISION DEFAULT NULL');
    }
}

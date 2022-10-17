<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221005183842 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs

        $this->addSql('ALTER TABLE orden DROP FOREIGN KEY FK_E128CFD7DADF1C23');
        $this->addSql('CREATE TABLE contrato (id INT AUTO_INCREMENT NOT NULL, cliente_id INT NOT NULL, plan_id INT DEFAULT NULL, parroquia_id INT DEFAULT NULL, vendedor_id INT DEFAULT NULL, solicitud_id INT DEFAULT NULL, numero VARCHAR(180) NOT NULL, fecha DATE NOT NULL, direccion VARCHAR(255) NOT NULL, estado VARCHAR(20) DEFAULT NULL, estado_contrato VARCHAR(20) DEFAULT NULL, valor_suscripcion DOUBLE PRECISION DEFAULT NULL, UNIQUE INDEX UNIQ_66696523F55AE19E (numero), INDEX IDX_66696523DE734E51 (cliente_id), INDEX IDX_66696523E899029B (plan_id), INDEX IDX_6669652374AFDC17 (parroquia_id), INDEX IDX_666965238361A8B8 (vendedor_id), UNIQUE INDEX UNIQ_666965231CB9D6E4 (solicitud_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE contrato ADD CONSTRAINT FK_66696523DE734E51 FOREIGN KEY (cliente_id) REFERENCES cliente (id)');
        $this->addSql('ALTER TABLE contrato ADD CONSTRAINT FK_66696523E899029B FOREIGN KEY (plan_id) REFERENCES servicio (id)');
        $this->addSql('ALTER TABLE contrato ADD CONSTRAINT FK_6669652374AFDC17 FOREIGN KEY (parroquia_id) REFERENCES parroquia (id)');
        $this->addSql('ALTER TABLE contrato ADD CONSTRAINT FK_666965238361A8B8 FOREIGN KEY (vendedor_id) REFERENCES colaborador (id)');
        $this->addSql('ALTER TABLE contrato ADD CONSTRAINT FK_666965231CB9D6E4 FOREIGN KEY (solicitud_id) REFERENCES solicitud (id)');
        $this->addSql('DROP TABLE san');
        $this->addSql('DROP INDEX IDX_E128CFD7DADF1C23 ON orden');
        $this->addSql('ALTER TABLE orden CHANGE san_id contrato_id INT NOT NULL');
        $this->addSql('ALTER TABLE orden ADD CONSTRAINT FK_E128CFD770AE7BF1 FOREIGN KEY (contrato_id) REFERENCES contrato (id)');
        $this->addSql('CREATE INDEX IDX_E128CFD770AE7BF1 ON orden (contrato_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE orden DROP FOREIGN KEY FK_E128CFD770AE7BF1');
        $this->addSql('CREATE TABLE san (id INT AUTO_INCREMENT NOT NULL, cliente_id INT NOT NULL, plan_id INT DEFAULT NULL, parroquia_id INT DEFAULT NULL, vendedor_id INT DEFAULT NULL, solicitud_id INT DEFAULT NULL, numero VARCHAR(180) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, fecha DATE NOT NULL, direccion VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, estado VARCHAR(20) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, estado_contrato VARCHAR(20) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, valor_suscripcion DOUBLE PRECISION DEFAULT NULL, INDEX IDX_7F1A19A274AFDC17 (parroquia_id), INDEX IDX_7F1A19A28361A8B8 (vendedor_id), INDEX IDX_7F1A19A2DE734E51 (cliente_id), INDEX IDX_7F1A19A2E899029B (plan_id), UNIQUE INDEX UNIQ_7F1A19A21CB9D6E4 (solicitud_id), UNIQUE INDEX UNIQ_7F1A19A2F55AE19E (numero), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE san ADD CONSTRAINT FK_7F1A19A21CB9D6E4 FOREIGN KEY (solicitud_id) REFERENCES solicitud (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE san ADD CONSTRAINT FK_7F1A19A274AFDC17 FOREIGN KEY (parroquia_id) REFERENCES parroquia (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE san ADD CONSTRAINT FK_7F1A19A28361A8B8 FOREIGN KEY (vendedor_id) REFERENCES colaborador (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE san ADD CONSTRAINT FK_7F1A19A2DE734E51 FOREIGN KEY (cliente_id) REFERENCES cliente (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE san ADD CONSTRAINT FK_7F1A19A2E899029B FOREIGN KEY (plan_id) REFERENCES servicio (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('DROP TABLE contrato');
        $this->addSql('DROP INDEX IDX_E128CFD770AE7BF1 ON orden');
        $this->addSql('ALTER TABLE orden CHANGE contrato_id san_id INT NOT NULL');
        $this->addSql('ALTER TABLE orden ADD CONSTRAINT FK_E128CFD7DADF1C23 FOREIGN KEY (san_id) REFERENCES san (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_E128CFD7DADF1C23 ON orden (san_id)');
    }
}

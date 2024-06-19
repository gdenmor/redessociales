<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240612175836 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE historia DROP FOREIGN KEY FK_435C8E7ADB38439E');
        $this->addSql('ALTER TABLE historia_interaccion DROP FOREIGN KEY FK_B230E772A144CCF');
        $this->addSql('ALTER TABLE historia_interaccion DROP FOREIGN KEY FK_B230E77F8FA80EF');
        $this->addSql('DROP TABLE historia');
        $this->addSql('DROP TABLE historia_interaccion');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE historia (id INT AUTO_INCREMENT NOT NULL, usuario_id INT DEFAULT NULL, archivo VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, fecha_historia DATE NOT NULL, INDEX IDX_435C8E7ADB38439E (usuario_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE historia_interaccion (historia_id INT NOT NULL, interaccion_id INT NOT NULL, INDEX IDX_B230E772A144CCF (interaccion_id), INDEX IDX_B230E77F8FA80EF (historia_id), PRIMARY KEY(historia_id, interaccion_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE historia ADD CONSTRAINT FK_435C8E7ADB38439E FOREIGN KEY (usuario_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE historia_interaccion ADD CONSTRAINT FK_B230E772A144CCF FOREIGN KEY (interaccion_id) REFERENCES interaccion (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE historia_interaccion ADD CONSTRAINT FK_B230E77F8FA80EF FOREIGN KEY (historia_id) REFERENCES historia (id) ON UPDATE NO ACTION ON DELETE CASCADE');
    }
}

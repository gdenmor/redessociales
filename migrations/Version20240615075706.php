<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240615075706 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE publicacion_interaccion DROP FOREIGN KEY FK_36FEF1C12A144CCF');
        $this->addSql('ALTER TABLE publicacion_interaccion DROP FOREIGN KEY FK_36FEF1C19ACBB5E7');
        $this->addSql('DROP TABLE publicacion_interaccion');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE publicacion_interaccion (publicacion_id INT NOT NULL, interaccion_id INT NOT NULL, INDEX IDX_36FEF1C19ACBB5E7 (publicacion_id), INDEX IDX_36FEF1C12A144CCF (interaccion_id), PRIMARY KEY(publicacion_id, interaccion_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE publicacion_interaccion ADD CONSTRAINT FK_36FEF1C12A144CCF FOREIGN KEY (interaccion_id) REFERENCES interaccion (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE publicacion_interaccion ADD CONSTRAINT FK_36FEF1C19ACBB5E7 FOREIGN KEY (publicacion_id) REFERENCES publicacion (id) ON UPDATE NO ACTION ON DELETE CASCADE');
    }
}

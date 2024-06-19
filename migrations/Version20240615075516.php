<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240615075516 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE usuario_interaccion (id INT AUTO_INCREMENT NOT NULL, usuario_id INT DEFAULT NULL, publicacion_id INT DEFAULT NULL, interaccion_id INT DEFAULT NULL, INDEX IDX_C268600FDB38439E (usuario_id), INDEX IDX_C268600F9ACBB5E7 (publicacion_id), INDEX IDX_C268600F2A144CCF (interaccion_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE usuario_interaccion ADD CONSTRAINT FK_C268600FDB38439E FOREIGN KEY (usuario_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE usuario_interaccion ADD CONSTRAINT FK_C268600F9ACBB5E7 FOREIGN KEY (publicacion_id) REFERENCES publicacion (id)');
        $this->addSql('ALTER TABLE usuario_interaccion ADD CONSTRAINT FK_C268600F2A144CCF FOREIGN KEY (interaccion_id) REFERENCES interaccion (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE usuario_interaccion DROP FOREIGN KEY FK_C268600FDB38439E');
        $this->addSql('ALTER TABLE usuario_interaccion DROP FOREIGN KEY FK_C268600F9ACBB5E7');
        $this->addSql('ALTER TABLE usuario_interaccion DROP FOREIGN KEY FK_C268600F2A144CCF');
        $this->addSql('DROP TABLE usuario_interaccion');
    }
}

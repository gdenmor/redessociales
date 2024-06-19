<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240522085135 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comentario DROP FOREIGN KEY FK_4B91E7029ACBB5E7');
        $this->addSql('ALTER TABLE comentario DROP FOREIGN KEY FK_4B91E702DB38439E');
        $this->addSql('ALTER TABLE comentario ADD CONSTRAINT FK_4B91E7029ACBB5E7 FOREIGN KEY (publicacion_id) REFERENCES publicacion (id)');
        $this->addSql('ALTER TABLE comentario ADD CONSTRAINT FK_4B91E702DB38439E FOREIGN KEY (usuario_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE comentario_interaccion DROP FOREIGN KEY FK_346576C92A144CCF');
        $this->addSql('ALTER TABLE comentario_interaccion DROP FOREIGN KEY FK_346576C9F3F2D7EC');
        $this->addSql('ALTER TABLE comentario_interaccion ADD CONSTRAINT FK_346576C92A144CCF FOREIGN KEY (interaccion_id) REFERENCES interaccion (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE comentario_interaccion ADD CONSTRAINT FK_346576C9F3F2D7EC FOREIGN KEY (comentario_id) REFERENCES comentario (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE historia DROP FOREIGN KEY FK_435C8E7ADB38439E');
        $this->addSql('ALTER TABLE historia ADD CONSTRAINT FK_435C8E7ADB38439E FOREIGN KEY (usuario_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE historia_interaccion DROP FOREIGN KEY FK_B230E772A144CCF');
        $this->addSql('ALTER TABLE historia_interaccion DROP FOREIGN KEY FK_B230E77F8FA80EF');
        $this->addSql('ALTER TABLE historia_interaccion ADD CONSTRAINT FK_B230E772A144CCF FOREIGN KEY (interaccion_id) REFERENCES interaccion (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE historia_interaccion ADD CONSTRAINT FK_B230E77F8FA80EF FOREIGN KEY (historia_id) REFERENCES historia (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE mensaje DROP FOREIGN KEY FK_9B631D01386D8D01');
        $this->addSql('ALTER TABLE mensaje DROP FOREIGN KEY FK_9B631D016BDF87DF');
        $this->addSql('ALTER TABLE mensaje ADD CONSTRAINT FK_9B631D01386D8D01 FOREIGN KEY (receptor_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE mensaje ADD CONSTRAINT FK_9B631D016BDF87DF FOREIGN KEY (emisor_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE mensaje_interaccion DROP FOREIGN KEY FK_2829864C2A144CCF');
        $this->addSql('ALTER TABLE mensaje_interaccion DROP FOREIGN KEY FK_2829864C4C54F362');
        $this->addSql('ALTER TABLE mensaje_interaccion ADD CONSTRAINT FK_2829864C2A144CCF FOREIGN KEY (interaccion_id) REFERENCES interaccion (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE mensaje_interaccion ADD CONSTRAINT FK_2829864C4C54F362 FOREIGN KEY (mensaje_id) REFERENCES mensaje (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE publicacion DROP FOREIGN KEY FK_62F2085FDB38439E');
        $this->addSql('ALTER TABLE publicacion ADD CONSTRAINT FK_62F2085FDB38439E FOREIGN KEY (usuario_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE publicacion_interaccion DROP FOREIGN KEY FK_36FEF1C12A144CCF');
        $this->addSql('ALTER TABLE publicacion_interaccion DROP FOREIGN KEY FK_36FEF1C19ACBB5E7');
        $this->addSql('ALTER TABLE publicacion_interaccion ADD CONSTRAINT FK_36FEF1C12A144CCF FOREIGN KEY (interaccion_id) REFERENCES interaccion (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE publicacion_interaccion ADD CONSTRAINT FK_36FEF1C19ACBB5E7 FOREIGN KEY (publicacion_id) REFERENCES publicacion (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_seguidores DROP FOREIGN KEY FK_8DE669D4A5989C7A');
        $this->addSql('ALTER TABLE user_seguidores DROP FOREIGN KEY FK_8DE669D4BC7DCCF5');
        $this->addSql('ALTER TABLE user_seguidores ADD CONSTRAINT FK_8DE669D4A5989C7A FOREIGN KEY (usuario_source) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_seguidores ADD CONSTRAINT FK_8DE669D4BC7DCCF5 FOREIGN KEY (usuario_target) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_seguidos DROP FOREIGN KEY FK_78A4129EA5989C7A');
        $this->addSql('ALTER TABLE user_seguidos DROP FOREIGN KEY FK_78A4129EBC7DCCF5');
        $this->addSql('ALTER TABLE user_seguidos ADD CONSTRAINT FK_78A4129EA5989C7A FOREIGN KEY (usuario_source) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_seguidos ADD CONSTRAINT FK_78A4129EBC7DCCF5 FOREIGN KEY (usuario_target) REFERENCES user (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comentario DROP FOREIGN KEY FK_4B91E702DB38439E');
        $this->addSql('ALTER TABLE comentario DROP FOREIGN KEY FK_4B91E7029ACBB5E7');
        $this->addSql('ALTER TABLE comentario ADD CONSTRAINT FK_4B91E702DB38439E FOREIGN KEY (usuario_id) REFERENCES user (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE comentario ADD CONSTRAINT FK_4B91E7029ACBB5E7 FOREIGN KEY (publicacion_id) REFERENCES publicacion (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE comentario_interaccion DROP FOREIGN KEY FK_346576C9F3F2D7EC');
        $this->addSql('ALTER TABLE comentario_interaccion DROP FOREIGN KEY FK_346576C92A144CCF');
        $this->addSql('ALTER TABLE comentario_interaccion ADD CONSTRAINT FK_346576C9F3F2D7EC FOREIGN KEY (comentario_id) REFERENCES comentario (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE comentario_interaccion ADD CONSTRAINT FK_346576C92A144CCF FOREIGN KEY (interaccion_id) REFERENCES interaccion (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE mensaje DROP FOREIGN KEY FK_9B631D016BDF87DF');
        $this->addSql('ALTER TABLE mensaje DROP FOREIGN KEY FK_9B631D01386D8D01');
        $this->addSql('ALTER TABLE mensaje ADD CONSTRAINT FK_9B631D016BDF87DF FOREIGN KEY (emisor_id) REFERENCES user (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE mensaje ADD CONSTRAINT FK_9B631D01386D8D01 FOREIGN KEY (receptor_id) REFERENCES user (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE mensaje_interaccion DROP FOREIGN KEY FK_2829864C4C54F362');
        $this->addSql('ALTER TABLE mensaje_interaccion DROP FOREIGN KEY FK_2829864C2A144CCF');
        $this->addSql('ALTER TABLE mensaje_interaccion ADD CONSTRAINT FK_2829864C4C54F362 FOREIGN KEY (mensaje_id) REFERENCES mensaje (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE mensaje_interaccion ADD CONSTRAINT FK_2829864C2A144CCF FOREIGN KEY (interaccion_id) REFERENCES interaccion (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE publicacion_interaccion DROP FOREIGN KEY FK_36FEF1C19ACBB5E7');
        $this->addSql('ALTER TABLE publicacion_interaccion DROP FOREIGN KEY FK_36FEF1C12A144CCF');
        $this->addSql('ALTER TABLE publicacion_interaccion ADD CONSTRAINT FK_36FEF1C19ACBB5E7 FOREIGN KEY (publicacion_id) REFERENCES publicacion (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE publicacion_interaccion ADD CONSTRAINT FK_36FEF1C12A144CCF FOREIGN KEY (interaccion_id) REFERENCES interaccion (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_seguidos DROP FOREIGN KEY FK_78A4129EA5989C7A');
        $this->addSql('ALTER TABLE user_seguidos DROP FOREIGN KEY FK_78A4129EBC7DCCF5');
        $this->addSql('ALTER TABLE user_seguidos ADD CONSTRAINT FK_78A4129EA5989C7A FOREIGN KEY (usuario_source) REFERENCES user (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_seguidos ADD CONSTRAINT FK_78A4129EBC7DCCF5 FOREIGN KEY (usuario_target) REFERENCES user (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE historia DROP FOREIGN KEY FK_435C8E7ADB38439E');
        $this->addSql('ALTER TABLE historia ADD CONSTRAINT FK_435C8E7ADB38439E FOREIGN KEY (usuario_id) REFERENCES user (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_seguidores DROP FOREIGN KEY FK_8DE669D4A5989C7A');
        $this->addSql('ALTER TABLE user_seguidores DROP FOREIGN KEY FK_8DE669D4BC7DCCF5');
        $this->addSql('ALTER TABLE user_seguidores ADD CONSTRAINT FK_8DE669D4A5989C7A FOREIGN KEY (usuario_source) REFERENCES user (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_seguidores ADD CONSTRAINT FK_8DE669D4BC7DCCF5 FOREIGN KEY (usuario_target) REFERENCES user (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE historia_interaccion DROP FOREIGN KEY FK_B230E77F8FA80EF');
        $this->addSql('ALTER TABLE historia_interaccion DROP FOREIGN KEY FK_B230E772A144CCF');
        $this->addSql('ALTER TABLE historia_interaccion ADD CONSTRAINT FK_B230E77F8FA80EF FOREIGN KEY (historia_id) REFERENCES historia (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE historia_interaccion ADD CONSTRAINT FK_B230E772A144CCF FOREIGN KEY (interaccion_id) REFERENCES interaccion (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE publicacion DROP FOREIGN KEY FK_62F2085FDB38439E');
        $this->addSql('ALTER TABLE publicacion ADD CONSTRAINT FK_62F2085FDB38439E FOREIGN KEY (usuario_id) REFERENCES user (id) ON UPDATE CASCADE ON DELETE CASCADE');
    }
}

<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250508101327 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE song_request DROP FOREIGN KEY FK_36FC9EB04DA1E751');
        $this->addSql('ALTER TABLE song_request_utilisateur DROP FOREIGN KEY FK_EB7587B4FB88E14F');
        $this->addSql('ALTER TABLE song_request_utilisateur DROP FOREIGN KEY FK_EB7587B4F8D622B5');
        $this->addSql('ALTER TABLE song_request_vote DROP FOREIGN KEY FK_7FAF703DA76ED395');
        $this->addSql('ALTER TABLE song_request_vote DROP FOREIGN KEY FK_7FAF703DF8D622B5');
        $this->addSql('DROP TABLE song_request');
        $this->addSql('DROP TABLE song_request_utilisateur');
        $this->addSql('DROP TABLE song_request_vote');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE song_request (id INT AUTO_INCREMENT NOT NULL, requested_by_id INT NOT NULL, link LONGTEXT CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci`, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, title VARCHAR(255) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci`, author VARCHAR(255) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci`, state INT DEFAULT NULL, want_to_be_notified TINYINT(1) DEFAULT NULL, INDEX IDX_36FC9EB04DA1E751 (requested_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb3 COLLATE `utf8mb3_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE song_request_utilisateur (song_request_id INT NOT NULL, utilisateur_id INT NOT NULL, INDEX IDX_EB7587B4F8D622B5 (song_request_id), INDEX IDX_EB7587B4FB88E14F (utilisateur_id), PRIMARY KEY(song_request_id, utilisateur_id)) DEFAULT CHARACTER SET utf8mb3 COLLATE `utf8mb3_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE song_request_vote (id INT AUTO_INCREMENT NOT NULL, song_request_id INT NOT NULL, user_id INT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_7FAF703DF8D622B5 (song_request_id), INDEX IDX_7FAF703DA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb3 COLLATE `utf8mb3_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE song_request ADD CONSTRAINT FK_36FC9EB04DA1E751 FOREIGN KEY (requested_by_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE song_request_utilisateur ADD CONSTRAINT FK_EB7587B4FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE song_request_utilisateur ADD CONSTRAINT FK_EB7587B4F8D622B5 FOREIGN KEY (song_request_id) REFERENCES song_request (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE song_request_vote ADD CONSTRAINT FK_7FAF703DA76ED395 FOREIGN KEY (user_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE song_request_vote ADD CONSTRAINT FK_7FAF703DF8D622B5 FOREIGN KEY (song_request_id) REFERENCES song_request (id)');
    }
}

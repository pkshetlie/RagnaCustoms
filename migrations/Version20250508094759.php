<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250508094759 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA77E3C61F9');
        $this->addSql('ALTER TABLE overlay DROP FOREIGN KEY FK_B9FF3CBEFCFA9DAE');
        $this->addSql('ALTER TABLE overlay DROP FOREIGN KEY FK_B9FF3CBEA76ED395');
        $this->addSql('ALTER TABLE score_event DROP FOREIGN KEY FK_82E2C62FA76ED395');
        $this->addSql('ALTER TABLE score_event DROP FOREIGN KEY FK_82E2C62FB37F772E');
        $this->addSql('ALTER TABLE score_event DROP FOREIGN KEY FK_82E2C62F71F7E88B');
        $this->addSql('DROP TABLE event');
        $this->addSql('DROP TABLE overlay');
        $this->addSql('DROP TABLE score_event');
        $this->addSql('DROP INDEX created_at ON score');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE event (id INT AUTO_INCREMENT NOT NULL, owner_id INT DEFAULT NULL, label VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, description LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, banner VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, registration_start_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', registration_end_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', start_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', end_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', score_math VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, price_description LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, max_players INT NOT NULL, players VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, state VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, slug VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, song_difficulties VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_3BAE0AA77E3C61F9 (owner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE overlay (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, difficulty_id INT DEFAULT NULL, disposition LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, start_at DATETIME DEFAULT NULL, html LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, css LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, UNIQUE INDEX UNIQ_B9FF3CBEA76ED395 (user_id), UNIQUE INDEX UNIQ_B9FF3CBEFCFA9DAE (difficulty_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE score_event (id INT AUTO_INCREMENT NOT NULL, event_id INT DEFAULT NULL, song_difficulty_id INT DEFAULT NULL, user_id INT NOT NULL, combo_blue INT DEFAULT NULL, combo_yellow INT DEFAULT NULL, country LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, date_ragnarock LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, extra LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, hit INT DEFAULT NULL, hit_delta_average INT DEFAULT NULL, hit_percentage INT DEFAULT NULL, missed INT DEFAULT NULL, percentage_of_perfects INT DEFAULT NULL, plateform VARCHAR(50) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, raw_pp DOUBLE PRECISION DEFAULT NULL, score DOUBLE PRECISION NOT NULL, session LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, user_ragnarock LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, weighted_pp DOUBLE PRECISION DEFAULT NULL, played_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_82E2C62F71F7E88B (event_id), INDEX IDX_82E2C62FB37F772E (song_difficulty_id), INDEX IDX_82E2C62FA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA77E3C61F9 FOREIGN KEY (owner_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE overlay ADD CONSTRAINT FK_B9FF3CBEFCFA9DAE FOREIGN KEY (difficulty_id) REFERENCES song_difficulty (id)');
        $this->addSql('ALTER TABLE overlay ADD CONSTRAINT FK_B9FF3CBEA76ED395 FOREIGN KEY (user_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE score_event ADD CONSTRAINT FK_82E2C62FA76ED395 FOREIGN KEY (user_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE score_event ADD CONSTRAINT FK_82E2C62FB37F772E FOREIGN KEY (song_difficulty_id) REFERENCES song_difficulty (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE score_event ADD CONSTRAINT FK_82E2C62F71F7E88B FOREIGN KEY (event_id) REFERENCES event (id)');
        $this->addSql('CREATE INDEX created_at ON score (created_at)');
    }
}

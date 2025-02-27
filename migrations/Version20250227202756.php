<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250227202756 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE event (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, banner VARCHAR(255) NOT NULL, registration_start_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', registration_end_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', start_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', end_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', score_math VARCHAR(255) NOT NULL, price_description LONGTEXT DEFAULT NULL, max_players INT NOT NULL, players VARCHAR(255) DEFAULT NULL, state VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, song_difficulties VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE score_event (id INT AUTO_INCREMENT NOT NULL, event_id INT DEFAULT NULL, song_difficulty_id INT DEFAULT NULL, user_id INT NOT NULL, combo_blue INT DEFAULT NULL, combo_yellow INT DEFAULT NULL, country LONGTEXT DEFAULT NULL, date_ragnarock LONGTEXT DEFAULT NULL, extra LONGTEXT DEFAULT NULL, hit INT DEFAULT NULL, hit_delta_average INT DEFAULT NULL, hit_percentage INT DEFAULT NULL, missed INT DEFAULT NULL, percentage_of_perfects INT DEFAULT NULL, plateform VARCHAR(50) DEFAULT NULL, raw_pp DOUBLE PRECISION DEFAULT NULL, score DOUBLE PRECISION NOT NULL, session LONGTEXT DEFAULT NULL, user_ragnarock LONGTEXT DEFAULT NULL, weighted_pp DOUBLE PRECISION DEFAULT NULL, played_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_82E2C62F71F7E88B (event_id), INDEX IDX_82E2C62FB37F772E (song_difficulty_id), INDEX IDX_82E2C62FA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE score_event ADD CONSTRAINT FK_82E2C62F71F7E88B FOREIGN KEY (event_id) REFERENCES event (id)');
        $this->addSql('ALTER TABLE score_event ADD CONSTRAINT FK_82E2C62FB37F772E FOREIGN KEY (song_difficulty_id) REFERENCES song_difficulty (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE score_event ADD CONSTRAINT FK_82E2C62FA76ED395 FOREIGN KEY (user_id) REFERENCES utilisateur (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE score_event DROP FOREIGN KEY FK_82E2C62F71F7E88B');
        $this->addSql('ALTER TABLE score_event DROP FOREIGN KEY FK_82E2C62FB37F772E');
        $this->addSql('ALTER TABLE score_event DROP FOREIGN KEY FK_82E2C62FA76ED395');
        $this->addSql('DROP TABLE event');
        $this->addSql('DROP TABLE score_event');
    }
}

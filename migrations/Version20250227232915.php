<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250227232915 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE tournament (id INT AUTO_INCREMENT NOT NULL, owner_id INT DEFAULT NULL, label VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, banner VARCHAR(255) NOT NULL, registration_start_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', registration_end_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', start_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', end_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', score_math VARCHAR(255) NOT NULL, price_description LONGTEXT DEFAULT NULL, max_players INT NOT NULL, state VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, song_difficulties VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_BD5FB8D97E3C61F9 (owner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tournament_player (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, tournament_id INT DEFAULT NULL, status VARCHAR(15) NOT NULL, INDEX IDX_FCB38435A76ED395 (user_id), INDEX IDX_FCB3843533D1A3E7 (tournament_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tournament_score (id INT AUTO_INCREMENT NOT NULL, tournament_id INT DEFAULT NULL, song_difficulty_id INT DEFAULT NULL, user_id INT NOT NULL, combo_blue INT DEFAULT NULL, combo_yellow INT DEFAULT NULL, country LONGTEXT DEFAULT NULL, date_ragnarock LONGTEXT DEFAULT NULL, extra LONGTEXT DEFAULT NULL, hit INT DEFAULT NULL, hit_delta_average INT DEFAULT NULL, hit_percentage INT DEFAULT NULL, missed INT DEFAULT NULL, percentage_of_perfects INT DEFAULT NULL, plateform VARCHAR(50) DEFAULT NULL, raw_pp DOUBLE PRECISION DEFAULT NULL, score DOUBLE PRECISION NOT NULL, session LONGTEXT DEFAULT NULL, user_ragnarock LONGTEXT DEFAULT NULL, weighted_pp DOUBLE PRECISION DEFAULT NULL, played_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_F3CFA74833D1A3E7 (tournament_id), INDEX IDX_F3CFA748B37F772E (song_difficulty_id), INDEX IDX_F3CFA748A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE tournament ADD CONSTRAINT FK_BD5FB8D97E3C61F9 FOREIGN KEY (owner_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE tournament_player ADD CONSTRAINT FK_FCB38435A76ED395 FOREIGN KEY (user_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE tournament_player ADD CONSTRAINT FK_FCB3843533D1A3E7 FOREIGN KEY (tournament_id) REFERENCES tournament (id)');
        $this->addSql('ALTER TABLE tournament_score ADD CONSTRAINT FK_F3CFA74833D1A3E7 FOREIGN KEY (tournament_id) REFERENCES tournament (id)');
        $this->addSql('ALTER TABLE tournament_score ADD CONSTRAINT FK_F3CFA748B37F772E FOREIGN KEY (song_difficulty_id) REFERENCES song_difficulty (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tournament_score ADD CONSTRAINT FK_F3CFA748A76ED395 FOREIGN KEY (user_id) REFERENCES utilisateur (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tournament DROP FOREIGN KEY FK_BD5FB8D97E3C61F9');
        $this->addSql('ALTER TABLE tournament_player DROP FOREIGN KEY FK_FCB38435A76ED395');
        $this->addSql('ALTER TABLE tournament_player DROP FOREIGN KEY FK_FCB3843533D1A3E7');
        $this->addSql('ALTER TABLE tournament_score DROP FOREIGN KEY FK_F3CFA74833D1A3E7');
        $this->addSql('ALTER TABLE tournament_score DROP FOREIGN KEY FK_F3CFA748B37F772E');
        $this->addSql('ALTER TABLE tournament_score DROP FOREIGN KEY FK_F3CFA748A76ED395');
        $this->addSql('DROP TABLE tournament');
        $this->addSql('DROP TABLE tournament_player');
        $this->addSql('DROP TABLE tournament_score');
    }
}

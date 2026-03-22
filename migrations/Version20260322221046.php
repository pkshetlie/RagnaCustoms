<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260322221046 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ranked_scores CHANGE total_ppscore total_pp_score DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('CREATE INDEX idx_ranked_scores_platform_score ON ranked_scores (plateform, total_pp_score)');
        $this->addSql('CREATE INDEX idx_ranked_scores_platform_user ON ranked_scores (plateform, user_id)');
        $this->addSql('ALTER TABLE ranked_scores RENAME INDEX idx_6cd4889aa76ed395 TO idx_ranked_scores_user');
        $this->addSql('ALTER TABLE reset_password_request CHANGE requested_at requested_at DATETIME NOT NULL, CHANGE expires_at expires_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE score CHANGE played_at played_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE score_history CHANGE played_at played_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE song CHANGE best_platform best_platform JSON DEFAULT NULL');
        $this->addSql('ALTER TABLE tournament CHANGE registration_start_at registration_start_at DATETIME NOT NULL, CHANGE registration_end_at registration_end_at DATETIME NOT NULL, CHANGE start_at start_at DATETIME NOT NULL, CHANGE end_at end_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE tournament_score CHANGE played_at played_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE utilisateur CHANGE roles roles JSON NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX idx_ranked_scores_platform_score ON ranked_scores');
        $this->addSql('DROP INDEX idx_ranked_scores_platform_user ON ranked_scores');
        $this->addSql('ALTER TABLE ranked_scores CHANGE total_pp_score total_ppscore DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE ranked_scores RENAME INDEX idx_ranked_scores_user TO IDX_6CD4889AA76ED395');
        $this->addSql('ALTER TABLE reset_password_request CHANGE requested_at requested_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', CHANGE expires_at expires_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE score CHANGE played_at played_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE score_history CHANGE played_at played_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE song CHANGE best_platform best_platform JSON DEFAULT NULL COMMENT \'(DC2Type:json)\'');
        $this->addSql('ALTER TABLE tournament CHANGE registration_start_at registration_start_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', CHANGE registration_end_at registration_end_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', CHANGE start_at start_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', CHANGE end_at end_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE tournament_score CHANGE played_at played_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE utilisateur CHANGE roles roles JSON NOT NULL COMMENT \'(DC2Type:json)\'');
    }
}

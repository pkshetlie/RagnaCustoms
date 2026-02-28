<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260228121050 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE song_difficulty_tournament (song_difficulty_id INT NOT NULL, tournament_id INT NOT NULL, INDEX IDX_7FAAA3C5B37F772E (song_difficulty_id), INDEX IDX_7FAAA3C533D1A3E7 (tournament_id), PRIMARY KEY(song_difficulty_id, tournament_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE song_difficulty_tournament ADD CONSTRAINT FK_7FAAA3C5B37F772E FOREIGN KEY (song_difficulty_id) REFERENCES song_difficulty (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE song_difficulty_tournament ADD CONSTRAINT FK_7FAAA3C533D1A3E7 FOREIGN KEY (tournament_id) REFERENCES tournament (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tournament DROP song_difficulties');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE song_difficulty_tournament DROP FOREIGN KEY FK_7FAAA3C5B37F772E');
        $this->addSql('ALTER TABLE song_difficulty_tournament DROP FOREIGN KEY FK_7FAAA3C533D1A3E7');
        $this->addSql('DROP TABLE song_difficulty_tournament');
        $this->addSql('ALTER TABLE tournament ADD song_difficulties VARCHAR(255) DEFAULT NULL');
    }
}

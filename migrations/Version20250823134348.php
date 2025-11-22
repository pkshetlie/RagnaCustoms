<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250823134348 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE song_difficulty_notation (id INT AUTO_INCREMENT NOT NULL, song_difficulty_id INT NOT NULL, user_id INT NOT NULL, note INT NOT NULL, INDEX IDX_E4C5B923B37F772E (song_difficulty_id), INDEX IDX_E4C5B923A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE song_difficulty_notation ADD CONSTRAINT FK_E4C5B923B37F772E FOREIGN KEY (song_difficulty_id) REFERENCES song_difficulty (id)');
        $this->addSql('ALTER TABLE song_difficulty_notation ADD CONSTRAINT FK_E4C5B923A76ED395 FOREIGN KEY (user_id) REFERENCES utilisateur (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE song_difficulty_notation DROP FOREIGN KEY FK_E4C5B923B37F772E');
        $this->addSql('ALTER TABLE song_difficulty_notation DROP FOREIGN KEY FK_E4C5B923A76ED395');
        $this->addSql('DROP TABLE song_difficulty_notation');
    }
}

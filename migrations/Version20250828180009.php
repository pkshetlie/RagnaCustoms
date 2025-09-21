<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250828180009 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE score CHANGE score score INT NOT NULL, CHANGE raw_pp raw_pp NUMERIC(8, 2) DEFAULT NULL, CHANGE session session VARCHAR(40) DEFAULT NULL, CHANGE date_ragnarock date_ragnarock VARCHAR(5) DEFAULT NULL, CHANGE user_ragnarock user_ragnarock VARCHAR(40) DEFAULT NULL, CHANGE country country VARCHAR(5) DEFAULT NULL, CHANGE plateform plateform VARCHAR(15) DEFAULT NULL, CHANGE weighted_pp weighted_pp NUMERIC(8, 2) DEFAULT NULL');
        $this->addSql('ALTER TABLE score_history CHANGE score score INT NOT NULL, CHANGE raw_pp raw_pp NUMERIC(8, 2) DEFAULT NULL, CHANGE session session VARCHAR(40) DEFAULT NULL, CHANGE date_ragnarock date_ragnarock VARCHAR(5) DEFAULT NULL, CHANGE user_ragnarock user_ragnarock VARCHAR(40) DEFAULT NULL, CHANGE country country VARCHAR(5) DEFAULT NULL, CHANGE plateform plateform VARCHAR(15) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE score CHANGE country country LONGTEXT DEFAULT NULL, CHANGE date_ragnarock date_ragnarock LONGTEXT DEFAULT NULL, CHANGE plateform plateform VARCHAR(50) DEFAULT NULL, CHANGE raw_pp raw_pp DOUBLE PRECISION DEFAULT NULL, CHANGE score score DOUBLE PRECISION NOT NULL, CHANGE session session LONGTEXT DEFAULT NULL, CHANGE user_ragnarock user_ragnarock LONGTEXT DEFAULT NULL, CHANGE weighted_pp weighted_pp DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE score_history CHANGE country country LONGTEXT DEFAULT NULL, CHANGE date_ragnarock date_ragnarock LONGTEXT DEFAULT NULL, CHANGE plateform plateform LONGTEXT DEFAULT NULL, CHANGE raw_pp raw_pp DOUBLE PRECISION DEFAULT NULL, CHANGE score score DOUBLE PRECISION NOT NULL, CHANGE session session LONGTEXT DEFAULT NULL, CHANGE user_ragnarock user_ragnarock LONGTEXT DEFAULT NULL');
    }
}

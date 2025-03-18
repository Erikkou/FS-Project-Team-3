<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250317212753 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE prediction (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, match_id INT NOT NULL, home_team_score INT NOT NULL, away_team_score INT NOT NULL, points INT DEFAULT 0 NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_36396FC8A76ED395 (user_id), INDEX IDX_36396FC82ABEACD6 (match_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE prediction ADD CONSTRAINT FK_36396FC8A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE prediction ADD CONSTRAINT FK_36396FC82ABEACD6 FOREIGN KEY (match_id) REFERENCES calendar (id)');
        $this->addSql('ALTER TABLE calendar ADD CONSTRAINT FK_6EA9A146E5C617D0 FOREIGN KEY (home_team) REFERENCES team (id)');
        $this->addSql('ALTER TABLE calendar ADD CONSTRAINT FK_6EA9A146558F2381 FOREIGN KEY (away_team) REFERENCES team (id)');
        $this->addSql('ALTER TABLE calendar ADD CONSTRAINT FK_6EA9A1467E860E36 FOREIGN KEY (stadium_id) REFERENCES stadium (id)');
        $this->addSql('ALTER TABLE calendar ADD CONSTRAINT FK_6EA9A146A6005CA0 FOREIGN KEY (round_id) REFERENCES rounds (id)');
        $this->addSql('CREATE INDEX IDX_6EA9A146E5C617D0 ON calendar (home_team)');
        $this->addSql('CREATE INDEX IDX_6EA9A146558F2381 ON calendar (away_team)');
        $this->addSql('CREATE INDEX IDX_6EA9A1467E860E36 ON calendar (stadium_id)');
        $this->addSql('CREATE INDEX IDX_6EA9A146A6005CA0 ON calendar (round_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE matches (id INT NOT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, starting_at VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, end_result_info VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, stage_id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE prediction DROP FOREIGN KEY FK_36396FC8A76ED395');
        $this->addSql('ALTER TABLE prediction DROP FOREIGN KEY FK_36396FC82ABEACD6');
        $this->addSql('DROP TABLE prediction');
        $this->addSql('ALTER TABLE calendar DROP FOREIGN KEY FK_6EA9A146E5C617D0');
        $this->addSql('ALTER TABLE calendar DROP FOREIGN KEY FK_6EA9A146558F2381');
        $this->addSql('ALTER TABLE calendar DROP FOREIGN KEY FK_6EA9A1467E860E36');
        $this->addSql('ALTER TABLE calendar DROP FOREIGN KEY FK_6EA9A146A6005CA0');
        $this->addSql('DROP INDEX IDX_6EA9A146E5C617D0 ON calendar');
        $this->addSql('DROP INDEX IDX_6EA9A146558F2381 ON calendar');
        $this->addSql('DROP INDEX IDX_6EA9A1467E860E36 ON calendar');
        $this->addSql('DROP INDEX IDX_6EA9A146A6005CA0 ON calendar');
    }
}

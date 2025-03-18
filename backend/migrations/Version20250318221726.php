<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250318221726 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE blog (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, img VARCHAR(255) DEFAULT NULL, author VARCHAR(255) NOT NULL, date DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE calendar (id INT AUTO_INCREMENT NOT NULL, home_team INT NOT NULL, away_team INT NOT NULL, stadium_id INT NOT NULL, round_id INT NOT NULL, starting_at DATE NOT NULL, status VARCHAR(50) NOT NULL, home_score INT DEFAULT NULL, away_score INT DEFAULT NULL, INDEX IDX_6EA9A146E5C617D0 (home_team), INDEX IDX_6EA9A146558F2381 (away_team), INDEX IDX_6EA9A1467E860E36 (stadium_id), INDEX IDX_6EA9A146A6005CA0 (round_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE player (id INT NOT NULL, team_id INT NOT NULL, name VARCHAR(255) NOT NULL, display_name VARCHAR(255) NOT NULL, jersey_number INT DEFAULT NULL, position_id INT DEFAULT NULL, detailed_position_id INT DEFAULT NULL, price VARCHAR(255) NOT NULL, INDEX IDX_98197A65296CD8AE (team_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE prediction (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, match_id INT NOT NULL, home_team_score INT NOT NULL, away_team_score INT NOT NULL, points INT DEFAULT 0 NOT NULL, status VARCHAR(20) NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_36396FC8A76ED395 (user_id), INDEX IDX_36396FC82ABEACD6 (match_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rounds (id INT NOT NULL, name VARCHAR(255) NOT NULL, start_at DATE NOT NULL, end_at DATE NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE stadium (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE team (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, roles JSON NOT NULL COMMENT \'(DC2Type:json)\', avatar VARCHAR(255) DEFAULT NULL, scores INT NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql("
            INSERT INTO `user` (`id`, `username`, `email`, `password`, `roles`, `avatar`, `scores`) VALUES
            (3, 'testuser', 'test@test', '\$2y\$13\$LK5Nt17SSy3cq.6nuBecaOaQ5hv59sPna1bbBoZhxttV2uZXhNR7y', '[\"ROLE_USER\"]', '/uploads/avatars/67bc9ade6b962.jpg', 18),
            (5, 'Nver', 'nver.am@live.nl', '\$2y\$13\$M5fFHID/FjBn2D9ArhS3Bu7iH2UtppD7rLYa/0jC3ZwH/aS1DnmOW', '[\"ROLE_USER\"]', '/uploads/avatars/67cc812f86333.jpg', 20);
        ");
        $this->addSql('ALTER TABLE calendar ADD CONSTRAINT FK_6EA9A146E5C617D0 FOREIGN KEY (home_team) REFERENCES team (id)');
        $this->addSql('ALTER TABLE calendar ADD CONSTRAINT FK_6EA9A146558F2381 FOREIGN KEY (away_team) REFERENCES team (id)');
        $this->addSql('ALTER TABLE calendar ADD CONSTRAINT FK_6EA9A1467E860E36 FOREIGN KEY (stadium_id) REFERENCES stadium (id)');
        $this->addSql('ALTER TABLE calendar ADD CONSTRAINT FK_6EA9A146A6005CA0 FOREIGN KEY (round_id) REFERENCES rounds (id)');
        $this->addSql('ALTER TABLE player ADD CONSTRAINT FK_98197A65296CD8AE FOREIGN KEY (team_id) REFERENCES team (id)');
        $this->addSql('ALTER TABLE prediction ADD CONSTRAINT FK_36396FC8A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE prediction ADD CONSTRAINT FK_36396FC82ABEACD6 FOREIGN KEY (match_id) REFERENCES calendar (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE calendar DROP FOREIGN KEY FK_6EA9A146E5C617D0');
        $this->addSql('ALTER TABLE calendar DROP FOREIGN KEY FK_6EA9A146558F2381');
        $this->addSql('ALTER TABLE calendar DROP FOREIGN KEY FK_6EA9A1467E860E36');
        $this->addSql('ALTER TABLE calendar DROP FOREIGN KEY FK_6EA9A146A6005CA0');
        $this->addSql('ALTER TABLE player DROP FOREIGN KEY FK_98197A65296CD8AE');
        $this->addSql('ALTER TABLE prediction DROP FOREIGN KEY FK_36396FC8A76ED395');
        $this->addSql('ALTER TABLE prediction DROP FOREIGN KEY FK_36396FC82ABEACD6');
        $this->addSql('DROP TABLE blog');
        $this->addSql('DROP TABLE calendar');
        $this->addSql('DROP TABLE player');
        $this->addSql('DROP TABLE prediction');
        $this->addSql('DROP TABLE rounds');
        $this->addSql('DROP TABLE stadium');
        $this->addSql('DROP TABLE team');
        $this->addSql('DROP TABLE user');
    }
}

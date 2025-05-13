<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250513134103 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE game (id INT AUTO_INCREMENT NOT NULL, score INT DEFAULT 0 NOT NULL, date DATETIME NOT NULL, user_id INT NOT NULL, INDEX IDX_232B318CA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE game_drawing (game_id INT NOT NULL, drawing_id INT NOT NULL, INDEX IDX_CA9E0C8E48FD905 (game_id), INDEX IDX_CA9E0C8E6552D89 (drawing_id), PRIMARY KEY(game_id, drawing_id)) DEFAULT CHARACTER SET utf8mb4
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE game ADD CONSTRAINT FK_232B318CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE game_drawing ADD CONSTRAINT FK_CA9E0C8E48FD905 FOREIGN KEY (game_id) REFERENCES game (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE game_drawing ADD CONSTRAINT FK_CA9E0C8E6552D89 FOREIGN KEY (drawing_id) REFERENCES drawing (id) ON DELETE CASCADE
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE game DROP FOREIGN KEY FK_232B318CA76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE game_drawing DROP FOREIGN KEY FK_CA9E0C8E48FD905
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE game_drawing DROP FOREIGN KEY FK_CA9E0C8E6552D89
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE game
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE game_drawing
        SQL);
    }
}

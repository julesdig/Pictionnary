<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250602201619 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
       /* $this->addSql(<<<'SQL'
            CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4
        SQL);
        $this->addSql(<<<'SQL'
            INSERT INTO category (name) VALUES 
            ('Formes et symboles'),
            ('Objets du quotidien'),
            ('Corps humain et vêtements'),
            ('Nature & transport')
        SQL);


          $this->addSql(<<<'SQL'
              ALTER TABLE game_drawing DROP FOREIGN KEY FK_CA9E0C8E48FD905
          SQL);
          $this->addSql(<<<'SQL'
              ALTER TABLE game_drawing DROP FOREIGN KEY FK_CA9E0C8E6552D89
          SQL);
          $this->addSql(<<<'SQL'
              DROP TABLE game_drawing
          SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE drawing ADD game_id INT default NULL, ADD category_id INT
        SQL);*/
        $this->addSql(<<<'SQL'
            UPDATE drawing SET category_id = (SELECT id FROM category WHERE name = 'Formes et symboles')
            WHERE word IN ('zigzag', 'octagon', 'diamond')
        SQL);
        $this->addSql(<<<'SQL'
            UPDATE drawing SET category_id = (SELECT id FROM category WHERE name = 'Objets du quotidien')
            WHERE word IN ('crayon', 'envelope')
        SQL);
        $this->addSql(<<<'SQL'
            UPDATE drawing SET category_id = (SELECT id FROM category WHERE name = 'Corps humain et vêtements')
            WHERE word IN ('eye', 'pants')
        SQL);
        $this->addSql(<<<'SQL'
            UPDATE drawing SET category_id = (SELECT id FROM category WHERE name = 'Nature & transport')
            WHERE word IN ('mushroom', 'wheel', 'airplane')
        SQL);

        $this->addSql(<<<'SQL'
            ALTER TABLE drawing CHANGE category_id category_id INT NOT NULL
        SQL);

    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE game_drawing (game_id INT NOT NULL, drawing_id INT NOT NULL, INDEX IDX_CA9E0C8E48FD905 (game_id), INDEX IDX_CA9E0C8E6552D89 (drawing_id), PRIMARY KEY(game_id, drawing_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_0900_ai_ci` ENGINE = InnoDB COMMENT = '' 
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE game_drawing ADD CONSTRAINT FK_CA9E0C8E48FD905 FOREIGN KEY (game_id) REFERENCES game (id) ON UPDATE NO ACTION ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE game_drawing ADD CONSTRAINT FK_CA9E0C8E6552D89 FOREIGN KEY (drawing_id) REFERENCES drawing (id) ON UPDATE NO ACTION ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE category
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE drawing DROP FOREIGN KEY FK_996B9FE7E48FD905
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE drawing DROP FOREIGN KEY FK_996B9FE712469DE2
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_996B9FE7E48FD905 ON drawing
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_996B9FE712469DE2 ON drawing
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE drawing DROP game_id, DROP category_id
        SQL);
    }
}

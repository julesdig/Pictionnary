<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250603061415 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE drawing DROP FOREIGN KEY FK_996B9FE712469DE2
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_996B9FE712469DE2 ON drawing
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE drawing DROP category_id
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE game ADD category_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE game ADD CONSTRAINT FK_232B318C12469DE2 FOREIGN KEY (category_id) REFERENCES category (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_232B318C12469DE2 ON game (category_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE drawing ADD category_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE drawing ADD CONSTRAINT FK_996B9FE712469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON UPDATE NO ACTION ON DELETE NO ACTION
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_996B9FE712469DE2 ON drawing (category_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE game DROP FOREIGN KEY FK_232B318C12469DE2
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_232B318C12469DE2 ON game
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE game DROP category_id
        SQL);
    }
}

<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250602211928 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE drawing ADD CONSTRAINT FK_996B9FE712469DE2 FOREIGN KEY (category_id) REFERENCES category (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_996B9FE712469DE2 ON drawing (category_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE drawing RENAME INDEX fk_996b9fe7e48fd905 TO IDX_996B9FE7E48FD905
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE drawing DROP FOREIGN KEY FK_996B9FE712469DE2
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_996B9FE712469DE2 ON drawing
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE drawing RENAME INDEX idx_996b9fe7e48fd905 TO FK_996B9FE7E48FD905
        SQL);
    }
}

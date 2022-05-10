<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220511105928 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE articles (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, slug VARCHAR(255) NOT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, body VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , updated_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , author_id VARCHAR(255) NOT NULL, favorites_count INTEGER NOT NULL)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_BFDD3168989D9B62 ON articles (slug)');
        $this->addSql('CREATE TABLE comments (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, author_id VARCHAR(255) NOT NULL, body VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , updated_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        )');
        $this->addSql('CREATE TABLE favourited_articles (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, article_id INTEGER NOT NULL, user_id INTEGER NOT NULL)');
        $this->addSql('CREATE INDEX IDX_4E8132137294869C ON favourited_articles (article_id)');
        $this->addSql('CREATE INDEX IDX_4E813213A76ED395 ON favourited_articles (user_id)');
        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles CLOB NOT NULL --(DC2Type:json)
        , password VARCHAR(255) NOT NULL, username VARCHAR(180) NOT NULL, bio VARCHAR(255) DEFAULT NULL, image VARCHAR(255) DEFAULT NULL)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649F85E0677 ON user (username)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE articles');
        $this->addSql('DROP TABLE comments');
        $this->addSql('DROP TABLE favourited_articles');
        $this->addSql('DROP TABLE user');
    }
}

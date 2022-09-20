<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220511201600 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE articles ADD COLUMN tags CLOB DEFAULT NULL');
        $this->addSql('DROP INDEX IDX_4E8132137294869C');
        $this->addSql('DROP INDEX IDX_4E813213A76ED395');
        $this->addSql('CREATE TEMPORARY TABLE __temp__favourited_articles AS SELECT id, article_id, user_id FROM favourited_articles');
        $this->addSql('DROP TABLE favourited_articles');
        $this->addSql('CREATE TABLE favourited_articles (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, article_id INTEGER NOT NULL, user_id INTEGER NOT NULL, CONSTRAINT FK_4E8132137294869C FOREIGN KEY (article_id) REFERENCES articles (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_4E813213A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO favourited_articles (id, article_id, user_id) SELECT id, article_id, user_id FROM __temp__favourited_articles');
        $this->addSql('DROP TABLE __temp__favourited_articles');
        $this->addSql('CREATE INDEX IDX_4E8132137294869C ON favourited_articles (article_id)');
        $this->addSql('CREATE INDEX IDX_4E813213A76ED395 ON favourited_articles (user_id)');
        $this->addSql('DROP INDEX UNIQ_8D93D649F85E0677');
        $this->addSql('CREATE TEMPORARY TABLE __temp__user AS SELECT id, email, roles, password, username, bio, image, followed_users FROM user');
        $this->addSql('DROP TABLE user');
        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles CLOB NOT NULL --(DC2Type:json)
        , password VARCHAR(255) NOT NULL, username VARCHAR(180) NOT NULL, bio VARCHAR(255) DEFAULT NULL, image VARCHAR(255) DEFAULT NULL, followed_users CLOB DEFAULT NULL --(DC2Type:array)
        )');
        $this->addSql('INSERT INTO user (id, email, roles, password, username, bio, image, followed_users) SELECT id, email, roles, password, username, bio, image, followed_users FROM __temp__user');
        $this->addSql('DROP TABLE __temp__user');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649F85E0677 ON user (username)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_BFDD3168989D9B62');
        $this->addSql('CREATE TEMPORARY TABLE __temp__articles AS SELECT id, slug, title, description, body, created_at, updated_at, author_id, favorites_count FROM articles');
        $this->addSql('DROP TABLE articles');
        $this->addSql('CREATE TABLE articles (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, slug VARCHAR(255) NOT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, body VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , updated_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , author_id VARCHAR(255) NOT NULL, favorites_count INTEGER NOT NULL)');
        $this->addSql('INSERT INTO articles (id, slug, title, description, body, created_at, updated_at, author_id, favorites_count) SELECT id, slug, title, description, body, created_at, updated_at, author_id, favorites_count FROM __temp__articles');
        $this->addSql('DROP TABLE __temp__articles');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_BFDD3168989D9B62 ON articles (slug)');
        $this->addSql('DROP INDEX IDX_4E8132137294869C');
        $this->addSql('DROP INDEX IDX_4E813213A76ED395');
        $this->addSql('CREATE TEMPORARY TABLE __temp__favourited_articles AS SELECT id, article_id, user_id FROM favourited_articles');
        $this->addSql('DROP TABLE favourited_articles');
        $this->addSql('CREATE TABLE favourited_articles (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, article_id INTEGER NOT NULL, user_id INTEGER NOT NULL)');
        $this->addSql('INSERT INTO favourited_articles (id, article_id, user_id) SELECT id, article_id, user_id FROM __temp__favourited_articles');
        $this->addSql('DROP TABLE __temp__favourited_articles');
        $this->addSql('CREATE INDEX IDX_4E8132137294869C ON favourited_articles (article_id)');
        $this->addSql('CREATE INDEX IDX_4E813213A76ED395 ON favourited_articles (user_id)');
        $this->addSql('DROP INDEX UNIQ_8D93D649F85E0677');
        $this->addSql('CREATE TEMPORARY TABLE __temp__user AS SELECT id, email, roles, password, username, bio, image, followed_users FROM user');
        $this->addSql('DROP TABLE user');
        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles CLOB NOT NULL --(DC2Type:json)
        , password VARCHAR(255) NOT NULL, username VARCHAR(180) NOT NULL, bio VARCHAR(255) DEFAULT NULL, image VARCHAR(255) DEFAULT NULL, followed_users CLOB DEFAULT NULL)');
        $this->addSql('INSERT INTO user (id, email, roles, password, username, bio, image, followed_users) SELECT id, email, roles, password, username, bio, image, followed_users FROM __temp__user');
        $this->addSql('DROP TABLE __temp__user');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649F85E0677 ON user (username)');
    }
}

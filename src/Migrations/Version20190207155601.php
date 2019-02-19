<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190207155601 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE fk_help_question (id INT AUTO_INCREMENT NOT NULL, category_id_id INT NOT NULL, headline VARCHAR(255) NOT NULL, body LONGTEXT DEFAULT NULL, rank INT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, slug VARCHAR(50) NOT NULL, INDEX IDX_5D86B88C9777D11E (category_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fk_help_category (id INT AUTO_INCREMENT NOT NULL, headline VARCHAR(255) NOT NULL, body LONGTEXT DEFAULT NULL, rank INT NOT NULL, is_active TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, slug VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE fk_help_question ADD CONSTRAINT FK_5D86B88C9777D11E FOREIGN KEY (category_id_id) REFERENCES fk_help_category (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE fk_help_question DROP FOREIGN KEY FK_5D86B88C9777D11E');
        $this->addSql('DROP TABLE fk_help_question');
        $this->addSql('DROP TABLE fk_help_category');
    }
}

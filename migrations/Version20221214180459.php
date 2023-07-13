<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221214180459 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE categorie_question (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(55) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE question ADD categorie_question_id INT DEFAULT NULL, DROP cat_question');
        $this->addSql('ALTER TABLE question ADD CONSTRAINT FK_B6F7494EC1D9D0B6 FOREIGN KEY (categorie_question_id) REFERENCES categorie_question (id)');
        $this->addSql('CREATE INDEX IDX_B6F7494EC1D9D0B6 ON question (categorie_question_id)');
        $this->addSql('ALTER TABLE reponse ADD nombre_points INT DEFAULT 0 NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE question DROP FOREIGN KEY FK_B6F7494EC1D9D0B6');
        $this->addSql('DROP TABLE categorie_question');
        $this->addSql('ALTER TABLE reponse DROP nombre_points');
        $this->addSql('DROP INDEX IDX_B6F7494EC1D9D0B6 ON question');
        $this->addSql('ALTER TABLE question ADD cat_question VARCHAR(30) DEFAULT NULL, DROP categorie_question_id');
    }
}

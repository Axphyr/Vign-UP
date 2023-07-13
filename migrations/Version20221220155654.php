<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221220155654 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE conseil (id INT AUTO_INCREMENT NOT NULL, questionnaire_id INT NOT NULL, categorie_question_id INT DEFAULT NULL, txt_conseil LONGTEXT NOT NULL, note_maximal INT NOT NULL, INDEX IDX_3F3F0681CE07E8FF (questionnaire_id), INDEX IDX_3F3F0681C1D9D0B6 (categorie_question_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE conseil ADD CONSTRAINT FK_3F3F0681CE07E8FF FOREIGN KEY (questionnaire_id) REFERENCES questionnaire (id)');
        $this->addSql('ALTER TABLE conseil ADD CONSTRAINT FK_3F3F0681C1D9D0B6 FOREIGN KEY (categorie_question_id) REFERENCES categorie_question (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE conseil DROP FOREIGN KEY FK_3F3F0681CE07E8FF');
        $this->addSql('ALTER TABLE conseil DROP FOREIGN KEY FK_3F3F0681C1D9D0B6');
        $this->addSql('DROP TABLE conseil');
    }
}

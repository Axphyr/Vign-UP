<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221201200154 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE question ADD questionnaire_id INT NOT NULL');
        $this->addSql('ALTER TABLE question ADD CONSTRAINT FK_B6F7494ECE07E8FF FOREIGN KEY (questionnaire_id) REFERENCES questionnaire (id)');
        $this->addSql('CREATE INDEX IDX_B6F7494ECE07E8FF ON question (questionnaire_id)');
        $this->addSql('ALTER TABLE réponse ADD question_id INT NOT NULL');
        $this->addSql('ALTER TABLE réponse ADD CONSTRAINT FK_A9A5D2E61E27F6BF FOREIGN KEY (question_id) REFERENCES question (id)');
        $this->addSql('CREATE INDEX IDX_A9A5D2E61E27F6BF ON réponse (question_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE question DROP FOREIGN KEY FK_B6F7494ECE07E8FF');
        $this->addSql('DROP INDEX IDX_B6F7494ECE07E8FF ON question');
        $this->addSql('ALTER TABLE question DROP questionnaire_id');
        $this->addSql('ALTER TABLE réponse DROP FOREIGN KEY FK_A9A5D2E61E27F6BF');
        $this->addSql('DROP INDEX IDX_A9A5D2E61E27F6BF ON réponse');
        $this->addSql('ALTER TABLE réponse DROP question_id');
    }
}

<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221129173659 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE question CHANGE txt_question txt_question LONGTEXT NOT NULL');
        $this->addSql('ALTER TABLE réponse CHANGE txt_réponse txt_réponse LONGTEXT NOT NULL');
        $this->addSql('ALTER TABLE sujet ADD user_id INT NOT NULL');
        $this->addSql('ALTER TABLE sujet ADD CONSTRAINT FK_2E13599DA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('CREATE INDEX IDX_2E13599DA76ED395 ON sujet (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE question CHANGE txt_question txt_question VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE réponse CHANGE txt_réponse txt_réponse VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE sujet DROP FOREIGN KEY FK_2E13599DA76ED395');
        $this->addSql('DROP INDEX IDX_2E13599DA76ED395 ON sujet');
        $this->addSql('ALTER TABLE sujet DROP user_id');
    }
}

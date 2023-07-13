<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221213165219 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE reponse (id INT AUTO_INCREMENT NOT NULL, question_id INT NOT NULL, txt_reponse LONGTEXT NOT NULL, INDEX IDX_5FB6DEC71E27F6BF (question_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reponse_user (reponse_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_B1F89F0ACF18BB82 (reponse_id), INDEX IDX_B1F89F0AA76ED395 (user_id), PRIMARY KEY(reponse_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE reponse ADD CONSTRAINT FK_5FB6DEC71E27F6BF FOREIGN KEY (question_id) REFERENCES question (id)');
        $this->addSql('ALTER TABLE reponse_user ADD CONSTRAINT FK_B1F89F0ACF18BB82 FOREIGN KEY (reponse_id) REFERENCES reponse (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reponse_user ADD CONSTRAINT FK_B1F89F0AA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE réponse DROP FOREIGN KEY FK_A9A5D2E61E27F6BF');
        $this->addSql('ALTER TABLE réponse_user DROP FOREIGN KEY FK_5B097D14A76ED395');
        $this->addSql('ALTER TABLE réponse_user DROP FOREIGN KEY FK_5B097D14DE9C9971');
        $this->addSql('DROP TABLE réponse');
        $this->addSql('DROP TABLE réponse_user');
        $this->addSql('ALTER TABLE questionnaire CHANGE partie_connecté partie_connecte INT DEFAULT NULL, CHANGE role_connecté role_connecte LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE réponse (id INT AUTO_INCREMENT NOT NULL, question_id INT NOT NULL, txt_réponse LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_A9A5D2E61E27F6BF (question_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE réponse_user (réponse_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_5B097D14A76ED395 (user_id), INDEX IDX_5B097D14DE9C9971 (réponse_id), PRIMARY KEY(réponse_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE réponse ADD CONSTRAINT FK_A9A5D2E61E27F6BF FOREIGN KEY (question_id) REFERENCES question (id)');
        $this->addSql('ALTER TABLE réponse_user ADD CONSTRAINT FK_5B097D14A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE réponse_user ADD CONSTRAINT FK_5B097D14DE9C9971 FOREIGN KEY (réponse_id) REFERENCES réponse (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reponse DROP FOREIGN KEY FK_5FB6DEC71E27F6BF');
        $this->addSql('ALTER TABLE reponse_user DROP FOREIGN KEY FK_B1F89F0ACF18BB82');
        $this->addSql('ALTER TABLE reponse_user DROP FOREIGN KEY FK_B1F89F0AA76ED395');
        $this->addSql('DROP TABLE reponse');
        $this->addSql('DROP TABLE reponse_user');
        $this->addSql('ALTER TABLE questionnaire CHANGE partie_connecte partie_connecté INT DEFAULT NULL, CHANGE role_connecte role_connecté LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\'');
    }
}

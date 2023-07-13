<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221204192633 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE réponse_user (réponse_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_5B097D14DE9C9971 (réponse_id), INDEX IDX_5B097D14A76ED395 (user_id), PRIMARY KEY(réponse_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE réponse_user ADD CONSTRAINT FK_5B097D14DE9C9971 FOREIGN KEY (réponse_id) REFERENCES réponse (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE réponse_user ADD CONSTRAINT FK_5B097D14A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE questionnaire ADD image_presentation LONGBLOB DEFAULT NULL, ADD role_connecté LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\'');
        $this->addSql('ALTER TABLE user CHANGE email email VARCHAR(180) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON user (email)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE réponse_user DROP FOREIGN KEY FK_5B097D14DE9C9971');
        $this->addSql('ALTER TABLE réponse_user DROP FOREIGN KEY FK_5B097D14A76ED395');
        $this->addSql('DROP TABLE réponse_user');
        $this->addSql('ALTER TABLE questionnaire DROP image_presentation, DROP role_connecté');
        $this->addSql('DROP INDEX UNIQ_8D93D649E7927C74 ON `user`');
        $this->addSql('ALTER TABLE `user` CHANGE email email VARCHAR(50) NOT NULL');
    }
}

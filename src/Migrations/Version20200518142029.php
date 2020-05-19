<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200518142029 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE rating (id INT AUTO_INCREMENT NOT NULL, training_id INT NOT NULL, member_id INT NOT NULL, rate DOUBLE PRECISION NOT NULL, number_of_ratings INT NOT NULL, INDEX IDX_D8892622BEFD98D1 (training_id), INDEX IDX_D88926227597D3FE (member_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE rating ADD CONSTRAINT FK_D8892622BEFD98D1 FOREIGN KEY (training_id) REFERENCES training (id)');
        $this->addSql('ALTER TABLE rating ADD CONSTRAINT FK_D88926227597D3FE FOREIGN KEY (member_id) REFERENCES member (id)');
        $this->addSql('ALTER TABLE member_training CHANGE member_id member_id INT DEFAULT NULL, CHANGE training_id training_id INT DEFAULT NULL, CHANGE finished_training finished_training TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE super_admin CHANGE roles roles JSON NOT NULL');
        $this->addSql('ALTER TABLE trainer CHANGE roles roles JSON NOT NULL');
        $this->addSql('ALTER TABLE training CHANGE trainer_id trainer_id INT DEFAULT NULL, CHANGE price price DOUBLE PRECISION DEFAULT NULL, CHANGE rating rating DOUBLE PRECISION DEFAULT NULL, CHANGE number_of_ratings number_of_ratings INT DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE rating');
        $this->addSql('ALTER TABLE member_training CHANGE member_id member_id INT DEFAULT NULL, CHANGE training_id training_id INT DEFAULT NULL, CHANGE finished_training finished_training TINYINT(1) DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE super_admin CHANGE roles roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`');
        $this->addSql('ALTER TABLE trainer CHANGE roles roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`');
        $this->addSql('ALTER TABLE training CHANGE trainer_id trainer_id INT DEFAULT NULL, CHANGE price price DOUBLE PRECISION DEFAULT \'NULL\', CHANGE rating rating DOUBLE PRECISION DEFAULT \'NULL\', CHANGE number_of_ratings number_of_ratings INT DEFAULT NULL');
    }
}

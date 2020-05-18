<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200518135447 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE member_training CHANGE member_id member_id INT DEFAULT NULL, CHANGE training_id training_id INT DEFAULT NULL, CHANGE finished_training finished_training TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE super_admin CHANGE roles roles JSON NOT NULL');
        $this->addSql('ALTER TABLE trainer CHANGE roles roles JSON NOT NULL');
        $this->addSql('ALTER TABLE training ADD number_of_ratings INT DEFAULT NULL, CHANGE trainer_id trainer_id INT DEFAULT NULL, CHANGE price price DOUBLE PRECISION DEFAULT NULL, CHANGE rating rating DOUBLE PRECISION DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE member_training CHANGE member_id member_id INT DEFAULT NULL, CHANGE training_id training_id INT DEFAULT NULL, CHANGE finished_training finished_training TINYINT(1) DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE super_admin CHANGE roles roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`');
        $this->addSql('ALTER TABLE trainer CHANGE roles roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`');
        $this->addSql('ALTER TABLE training DROP number_of_ratings, CHANGE trainer_id trainer_id INT DEFAULT NULL, CHANGE price price DOUBLE PRECISION DEFAULT \'NULL\', CHANGE rating rating DOUBLE PRECISION DEFAULT \'NULL\'');
    }
}

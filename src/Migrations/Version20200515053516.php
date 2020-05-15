<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200515053516 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE courses (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(191) NOT NULL, trainer INT NOT NULL, place VARCHAR(255) NOT NULL, time DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE member_training DROP FOREIGN KEY FK_22CF29657597D3FE');
        $this->addSql('ALTER TABLE member_training DROP FOREIGN KEY FK_22CF2965BEFD98D1');
        $this->addSql('ALTER TABLE member_training ADD id INT AUTO_INCREMENT NOT NULL, ADD finished_training TINYINT(1) DEFAULT NULL, CHANGE member_id member_id INT DEFAULT NULL, CHANGE training_id training_id INT DEFAULT NULL, DROP PRIMARY KEY, ADD PRIMARY KEY (id)');
        $this->addSql('ALTER TABLE member_training ADD CONSTRAINT FK_22CF29657597D3FE FOREIGN KEY (member_id) REFERENCES member (id)');
        $this->addSql('ALTER TABLE member_training ADD CONSTRAINT FK_22CF2965BEFD98D1 FOREIGN KEY (training_id) REFERENCES training (id)');
        $this->addSql('ALTER TABLE super_admin CHANGE roles roles JSON NOT NULL');
        $this->addSql('ALTER TABLE trainer CHANGE roles roles JSON NOT NULL');
        $this->addSql('ALTER TABLE training CHANGE trainer_id trainer_id INT DEFAULT NULL, CHANGE price price DOUBLE PRECISION DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE courses');
        $this->addSql('ALTER TABLE member_training MODIFY id INT NOT NULL');
        $this->addSql('ALTER TABLE member_training DROP FOREIGN KEY FK_22CF29657597D3FE');
        $this->addSql('ALTER TABLE member_training DROP FOREIGN KEY FK_22CF2965BEFD98D1');
        $this->addSql('ALTER TABLE member_training DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE member_training DROP id, DROP finished_training, CHANGE member_id member_id INT NOT NULL, CHANGE training_id training_id INT NOT NULL');
        $this->addSql('ALTER TABLE member_training ADD CONSTRAINT FK_22CF29657597D3FE FOREIGN KEY (member_id) REFERENCES member (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE member_training ADD CONSTRAINT FK_22CF2965BEFD98D1 FOREIGN KEY (training_id) REFERENCES training (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE member_training ADD PRIMARY KEY (member_id, training_id)');
        $this->addSql('ALTER TABLE super_admin CHANGE roles roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`');
        $this->addSql('ALTER TABLE trainer CHANGE roles roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`');
        $this->addSql('ALTER TABLE training CHANGE trainer_id trainer_id INT DEFAULT NULL, CHANGE price price DOUBLE PRECISION DEFAULT \'NULL\'');
    }
}

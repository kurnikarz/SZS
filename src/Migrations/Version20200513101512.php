<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200513101512 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE member_training (member_id INT NOT NULL, training_id INT NOT NULL, INDEX IDX_22CF29657597D3FE (member_id), INDEX IDX_22CF2965BEFD98D1 (training_id), PRIMARY KEY(member_id, training_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE member_training ADD CONSTRAINT FK_22CF29657597D3FE FOREIGN KEY (member_id) REFERENCES member (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE member_training ADD CONSTRAINT FK_22CF2965BEFD98D1 FOREIGN KEY (training_id) REFERENCES training (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE member DROP FOREIGN KEY FK_70E4FA78BEFD98D1');
        $this->addSql('DROP INDEX IDX_70E4FA78BEFD98D1 ON member');
        $this->addSql('ALTER TABLE member DROP training_id, DROP roles, CHANGE password password VARCHAR(255) NOT NULL, CHANGE name name VARCHAR(50) NOT NULL, CHANGE surname surname VARCHAR(50) NOT NULL');
        $this->addSql('ALTER TABLE super_admin CHANGE roles roles JSON NOT NULL');
        $this->addSql('ALTER TABLE trainer ADD username VARCHAR(255) NOT NULL, CHANGE roles roles JSON NOT NULL, CHANGE name name VARCHAR(255) NOT NULL, CHANGE surname surname VARCHAR(255) NOT NULL, CHANGE email email VARCHAR(255) NOT NULL, CHANGE number number INT NOT NULL');
        $this->addSql('ALTER TABLE training CHANGE trainer_id trainer_id INT DEFAULT NULL, CHANGE name name VARCHAR(50) NOT NULL, CHANGE price price DOUBLE PRECISION DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE member_training');
        $this->addSql('ALTER TABLE member ADD training_id INT DEFAULT NULL, ADD roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`, CHANGE password password VARCHAR(191) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE name name VARCHAR(40) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE surname surname VARCHAR(40) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE member ADD CONSTRAINT FK_70E4FA78BEFD98D1 FOREIGN KEY (training_id) REFERENCES training (id)');
        $this->addSql('CREATE INDEX IDX_70E4FA78BEFD98D1 ON member (training_id)');
        $this->addSql('ALTER TABLE super_admin CHANGE roles roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`');
        $this->addSql('ALTER TABLE trainer DROP username, CHANGE roles roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`, CHANGE name name VARCHAR(50) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE surname surname VARCHAR(50) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE email email VARCHAR(50) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE number number VARCHAR(15) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE training CHANGE trainer_id trainer_id INT DEFAULT NULL, CHANGE name name VARCHAR(60) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE price price DOUBLE PRECISION DEFAULT \'NULL\'');
    }
}

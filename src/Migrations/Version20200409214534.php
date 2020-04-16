<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200409214534 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE super_admin (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(255) NOT NULL, roles LONGTEXT NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_BC8C2783F85E0677 (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE trainer (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE training (id INT AUTO_INCREMENT NOT NULL, trainer_id INT DEFAULT NULL, name VARCHAR(60) NOT NULL, description TINYTEXT NOT NULL, start_date DATE NOT NULL, end_date DATE NOT NULL, price DOUBLE PRECISION DEFAULT NULL, free TINYINT(1) NOT NULL, INDEX IDX_D5128A8FFB08EDF6 (trainer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE training ADD CONSTRAINT FK_D5128A8FFB08EDF6 FOREIGN KEY (trainer_id) REFERENCES trainer (id)');
        $this->addSql('ALTER TABLE courses CHANGE name name VARCHAR(191) NOT NULL');
        $this->addSql('ALTER TABLE member CHANGE password password VARCHAR(191) NOT NULL');
        $this->addSql('ALTER TABLE member ADD CONSTRAINT FK_70E4FA78BEFD98D1 FOREIGN KEY (training_id) REFERENCES training (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE training DROP FOREIGN KEY FK_D5128A8FFB08EDF6');
        $this->addSql('ALTER TABLE member DROP FOREIGN KEY FK_70E4FA78BEFD98D1');
        $this->addSql('DROP TABLE super_admin');
        $this->addSql('DROP TABLE trainer');
        $this->addSql('DROP TABLE training');
        $this->addSql('ALTER TABLE courses CHANGE name name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE member CHANGE password password VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}

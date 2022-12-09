<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221208060301 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE challenge DROP FOREIGN KEY FK_D7098951B03A8386');
        $this->addSql('DROP INDEX IDX_D7098951B03A8386 ON challenge');
        $this->addSql('ALTER TABLE challenge DROP completed, CHANGE created_by_id author_id INT NOT NULL');
        $this->addSql('ALTER TABLE challenge ADD CONSTRAINT FK_D7098951F675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_D7098951F675F31B ON challenge (author_id)');
        $this->addSql('ALTER TABLE user_challenge DROP FOREIGN KEY FK_D7E904B5A76ED395');
        $this->addSql('ALTER TABLE user_challenge DROP FOREIGN KEY FK_D7E904B598A21AC6');
        $this->addSql('ALTER TABLE user_challenge ADD id INT AUTO_INCREMENT NOT NULL, ADD completed TINYINT(1) NOT NULL, DROP PRIMARY KEY, ADD PRIMARY KEY (id)');
        $this->addSql('ALTER TABLE user_challenge ADD CONSTRAINT FK_D7E904B5A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_challenge ADD CONSTRAINT FK_D7E904B598A21AC6 FOREIGN KEY (challenge_id) REFERENCES challenge (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE challenge DROP FOREIGN KEY FK_D7098951F675F31B');
        $this->addSql('DROP INDEX IDX_D7098951F675F31B ON challenge');
        $this->addSql('ALTER TABLE challenge ADD completed TINYINT(1) NOT NULL, CHANGE author_id created_by_id INT NOT NULL');
        $this->addSql('ALTER TABLE challenge ADD CONSTRAINT FK_D7098951B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_D7098951B03A8386 ON challenge (created_by_id)');
        $this->addSql('ALTER TABLE user_challenge MODIFY id INT NOT NULL');
        $this->addSql('ALTER TABLE user_challenge DROP FOREIGN KEY FK_D7E904B5A76ED395');
        $this->addSql('ALTER TABLE user_challenge DROP FOREIGN KEY FK_D7E904B598A21AC6');
        $this->addSql('DROP INDEX `PRIMARY` ON user_challenge');
        $this->addSql('ALTER TABLE user_challenge DROP id, DROP completed');
        $this->addSql('ALTER TABLE user_challenge ADD CONSTRAINT FK_D7E904B5A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_challenge ADD CONSTRAINT FK_D7E904B598A21AC6 FOREIGN KEY (challenge_id) REFERENCES challenge (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_challenge ADD PRIMARY KEY (user_id, challenge_id)');
    }
}

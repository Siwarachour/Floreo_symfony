<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240307040852 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE dechets DROP FOREIGN KEY FK_56EF6EAFFD02F13');
        $this->addSql('DROP INDEX IDX_56EF6EAFFD02F13 ON dechets');
        $this->addSql('ALTER TABLE dechets ADD quantite INT NOT NULL, ADD image VARCHAR(255) NOT NULL, DROP quantité, CHANGE evenement_id reservation_dechets_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE dechets ADD CONSTRAINT FK_56EF6EAF1F04AF4F FOREIGN KEY (reservation_dechets_id) REFERENCES reservation_dechets (id)');
        $this->addSql('CREATE INDEX IDX_56EF6EAF1F04AF4F ON dechets (reservation_dechets_id)');
        $this->addSql('ALTER TABLE reservation_dechets ADD quantite INT NOT NULL, DROP quantité, CHANGE numero_tell numero_tell VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE dechets DROP FOREIGN KEY FK_56EF6EAF1F04AF4F');
        $this->addSql('DROP INDEX IDX_56EF6EAF1F04AF4F ON dechets');
        $this->addSql('ALTER TABLE dechets ADD quantité DOUBLE PRECISION NOT NULL, DROP quantite, DROP image, CHANGE reservation_dechets_id evenement_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE dechets ADD CONSTRAINT FK_56EF6EAFFD02F13 FOREIGN KEY (evenement_id) REFERENCES evenement (id)');
        $this->addSql('CREATE INDEX IDX_56EF6EAFFD02F13 ON dechets (evenement_id)');
        $this->addSql('ALTER TABLE reservation_dechets ADD quantité DOUBLE PRECISION NOT NULL, DROP quantite, CHANGE numero_tell numero_tell INT NOT NULL');
    }
}

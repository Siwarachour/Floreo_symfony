<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240307011812 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE don DROP FOREIGN KEY FK_F8F081D92EAB82C6');
        $this->addSql('DROP INDEX UNIQ_F8F081D92EAB82C6 ON don');
        $this->addSql('ALTER TABLE don ADD date_don DATE NOT NULL, DROP facture_don_id');
        $this->addSql('ALTER TABLE facture_don ADD don_id INT DEFAULT NULL, ADD adresses VARCHAR(255) NOT NULL, ADD numero_telephone INT NOT NULL, ADD description VARCHAR(255) NOT NULL, DROP date, DROP montant');
        $this->addSql('ALTER TABLE facture_don ADD CONSTRAINT FK_9D804C967B3C9061 FOREIGN KEY (don_id) REFERENCES don (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_9D804C967B3C9061 ON facture_don (don_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE don ADD facture_don_id INT DEFAULT NULL, DROP date_don');
        $this->addSql('ALTER TABLE don ADD CONSTRAINT FK_F8F081D92EAB82C6 FOREIGN KEY (facture_don_id) REFERENCES facture_don (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F8F081D92EAB82C6 ON don (facture_don_id)');
        $this->addSql('ALTER TABLE facture_don DROP FOREIGN KEY FK_9D804C967B3C9061');
        $this->addSql('DROP INDEX UNIQ_9D804C967B3C9061 ON facture_don');
        $this->addSql('ALTER TABLE facture_don ADD date DATE NOT NULL, ADD montant DOUBLE PRECISION NOT NULL, DROP don_id, DROP adresses, DROP numero_telephone, DROP description');
    }
}

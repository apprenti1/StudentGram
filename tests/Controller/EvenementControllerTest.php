<?php

namespace App\Test\Controller;

use App\Entity\Evenement;
use App\Repository\EvenementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class EvenementControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EvenementRepository $repository;
    private string $path = '/evenement/';
    private EntityManagerInterface $manager;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = static::getContainer()->get('doctrine')->getRepository(Evenement::class);

        foreach ($this->repository->findAll() as $object) {
            $this->manager->remove($object);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Evenement index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $originalNumObjectsInRepository = count($this->repository->findAll());

        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'evenement[nom]' => 'Testing',
            'evenement[description]' => 'Testing',
            'evenement[date]' => 'Testing',
            'evenement[heure]' => 'Testing',
            'evenement[duree]' => 'Testing',
            'evenement[ref_salle]' => 'Testing',
        ]);

        self::assertResponseRedirects('/evenement/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Evenement();
        $fixture->setNom('My Title');
        $fixture->setDescription('My Title');
        $fixture->setDate('My Title');
        $fixture->setHeure('My Title');
        $fixture->setDuree('My Title');
        $fixture->setRef_salle('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Evenement');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Evenement();
        $fixture->setNom('My Title');
        $fixture->setDescription('My Title');
        $fixture->setDate('My Title');
        $fixture->setHeure('My Title');
        $fixture->setDuree('My Title');
        $fixture->setRef_salle('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'evenement[nom]' => 'Something New',
            'evenement[description]' => 'Something New',
            'evenement[date]' => 'Something New',
            'evenement[heure]' => 'Something New',
            'evenement[duree]' => 'Something New',
            'evenement[ref_salle]' => 'Something New',
        ]);

        self::assertResponseRedirects('/evenement/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getNom());
        self::assertSame('Something New', $fixture[0]->getDescription());
        self::assertSame('Something New', $fixture[0]->getDate());
        self::assertSame('Something New', $fixture[0]->getHeure());
        self::assertSame('Something New', $fixture[0]->getDuree());
        self::assertSame('Something New', $fixture[0]->getRef_salle());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new Evenement();
        $fixture->setNom('My Title');
        $fixture->setDescription('My Title');
        $fixture->setDate('My Title');
        $fixture->setHeure('My Title');
        $fixture->setDuree('My Title');
        $fixture->setRef_salle('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/evenement/');
    }
}

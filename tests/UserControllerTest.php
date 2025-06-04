<?php

namespace App\Tests;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    private $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
    }

    public function testLogin(): void
    {
        $this->client->disableReboot();

        // Étape 1 : Connexion
        $this->client->request('GET', '/login');
        self::assertResponseIsSuccessful();
        $this->client->submitForm('Sign in', [
            '_username' => 'luc-honore-daniel@tele2.fr',
            '_password' => 'password123',
        ]);
        $crawler = $this->client->followRedirect();

        // Étape 2 : Aller sur la page du profil
        $crawler = $this->client->request('GET', '/profile');
        self::assertResponseIsSuccessful();

        // Étape 3 : Soumettre le formulaire pour modifier le profil
        $this->client->submitForm('Modifier mon profil', [
            'user_form[password]' => 'password123',
            'user_form[username]' => 'L-Honore',
            'user_form[fullname]' => 'LHDaniel',
        ]);

        // Étape 4 : Recharger la page du profil pour vérifier les changements
        $crawler = $this->client->request('GET', '/profile');
        self::assertResponseIsSuccessful();

        // Étape 5 : Vérifier que les données ont été mises à jour
        $entityManager = $this->client->getContainer()->get('doctrine.orm.entity_manager');
        $userRepository = $entityManager->getRepository(User::class);
        $updatedUser = $userRepository->findOneBy(['email' => 'luc-honore-daniel@tele2.fr']);

        // Afficher les informations pour vérifier
        dump($updatedUser->getUsername());
        dump($updatedUser->getFullname());
    }

}

<?php

declare(strict_types=1);

namespace App\Tests\Integration\Infrastructure\Controller\App\Profile;

use App\Tests\Integration\Infrastructure\Controller\AbstractWebTestCase;

final class DetailProfileControllerTest extends AbstractWebTestCase
{
    public function testProfile(): void
    {
        $client = $this->login();
        $crawler = $client->request('GET', '/app/profile/0b507871-8b5e-4575-b297-a630310fc06e'); // Mathieu

        $this->assertResponseStatusCodeSame(200);
        $this->assertSecurityHeaders();
        $this->assertMetaTitle('Mon profil - moment', $crawler);
        $this->assertSame('Mon profil', $crawler->filter('h1')->text());
        $this->assertSame('Mathieu M. Je suis un développeur Saint Ouen 33 ans Inscrit.e depuis Oct 2023', $crawler->filter('[data-testid="profile"] *')->filter(':not(.j-desktop-only)')->text());
        $this->assertSame('http://localhost/app/profile/edit', $crawler->filter('[data-testid="edit-link"]')->link()->getUri());
    }

    public function testOtherProfile(): void
    {
        $client = $this->login();
        $crawler = $client->request('GET', '/app/profile/d47badd9-989e-472b-a80e-9df642e93880'); // Gregory

        $this->assertResponseStatusCodeSame(200);
        $this->assertSecurityHeaders();
        $this->assertMetaTitle('Profil de Gregory P. - moment', $crawler);
        $this->assertSame('Profil de Gregory P.', $crawler->filter('h1')->text());
        $this->assertSame('Gregory P. Je suis cycliste Paris Inscrit.e depuis Sep 2023', $crawler->filter('[data-testid="profile"] *')->filter(':not(.j-desktop-only)')->text());
        $this->assertSame(0, $crawler->filter('[data-testid="edit-link"]')->count()); // No edit link
    }

    public function testProfileNotFound(): void
    {
        $client = $this->login();
        $client->request('GET', '/app/profile/12345678-8b5e-4575-b297-1230310fc061');

        $this->assertResponseStatusCodeSame(404);
    }

    public function testWithoutAuthenticatedUser(): void
    {
        $client = static::createClient();
        $client->request('GET', '/app/profile/0b507871-8b5e-4575-b297-a630310fc06e');

        $this->assertResponseRedirects('http://localhost/login', 302);
    }
}

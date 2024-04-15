<?php

declare(strict_types=1);

namespace App\Tests\Integration\Infrastructure\Controller\App\Event;

use App\Tests\Integration\Infrastructure\Controller\AbstractWebTestCase;

final class EditEventControllerTest extends AbstractWebTestCase
{
    public function testEdit(): void
    {
        $client = $this->login();
        $crawler = $client->request('GET', '/app/events/f1f992d3-3cf5-4eb2-9b83-f112b7234613/edit');

        $this->assertResponseStatusCodeSame(200);
        $this->assertSecurityHeaders();
        $this->assertSame('Modifier l\'évènement "Mariage H&M"', $crawler->filter('h1')->text());
        $this->assertMetaTitle('Modifier l\'évènement "Mariage H&M" - Moment', $crawler);

        $saveButton = $crawler->selectButton('Sauvegarder');
        $form = $saveButton->form();
        $form['event_form[title]'] = 'Mariage H&M2';
        $form['event_form[date]'] = '2035-02-12'; // Deliberately set a date far in the future
        $client->submit($form);
        $crawler = $client->followRedirect();

        $this->assertResponseStatusCodeSame(200);
        $this->assertRouteSame('app_events_dashboard');
        $this->assertSame('Mariage H&M2', $crawler->filter('h1')->text());
    }

    public function testInvalidData(): void
    {
        $client = $this->login();
        $crawler = $client->request('GET', '/app/events/f1f992d3-3cf5-4eb2-9b83-f112b7234613/edit');

        $saveButton = $crawler->selectButton('Sauvegarder');
        $form = $saveButton->form();

        // Empty data
        $form['event_form[title]'] = '';
        $form['event_form[date]'] = '';
        $crawler = $client->submit($form);

        $this->assertResponseStatusCodeSame(422);
        $this->assertSame('Cette valeur ne doit pas être vide.', $crawler->filter('#event_form_title_error')->text());
        $this->assertSame('Cette valeur ne doit pas être vide.', $crawler->filter('#event_form_date_error')->text());

        // Invalid data
        $form['event_form[title]'] = str_repeat('a', 101);
        $form['event_form[date]'] = 'abc';
        $crawler = $client->submit($form);

        $this->assertResponseStatusCodeSame(422);
        $this->assertSame('Cette chaîne est trop longue. Elle doit avoir au maximum 100 caractères.', $crawler->filter('#event_form_title_error')->text());
        $this->assertSame('Veuillez entrer une date valide.', $crawler->filter('#event_form_date_error')->text());

        // Date in the past
        $form['event_form[date]'] = '2024-01-01';
        $crawler = $client->submit($form);

        $this->assertResponseStatusCodeSame(422);
        $this->assertStringStartsWith('Cette valeur doit être supérieure à', $crawler->filter('#event_form_date_error')->text());
    }

    public function testEventAlreadyExist(): void
    {
        $client = $this->login();
        $crawler = $client->request('GET', '/app/events/f1f992d3-3cf5-4eb2-9b83-f112b7234613/edit');

        $saveButton = $crawler->selectButton('Sauvegarder');
        $form = $saveButton->form();

        $form['event_form[title]'] = 'Mariage A&A';
        $form['event_form[date]'] = '2035-01-01'; // Deliberately set a date far in the future
        $crawler = $client->submit($form);

        $this->assertResponseStatusCodeSame(422);
        $this->assertSame('Un évènement avec ce nom existe déjà.', $crawler->filter('#event_form_title_error')->text());
    }

    public function testEditAnEventNotOwned(): void
    {
        $client = $this->login('raphael.marchois@gmail.com');
        $client->request('GET', '/app/events/f1f992d3-3cf5-4eb2-9b83-f112b7234613/edit');

        $this->assertResponseStatusCodeSame(404);
    }

    public function testEditEventNotFound(): void
    {
        $client = $this->login();
        $client->request('GET', '/app/events/1d288130-7317-42b6-b2a7-7fd6cd0918de/edit');

        $this->assertResponseStatusCodeSame(404);
    }

    public function testEditInvalidUri(): void
    {
        $client = $this->login();
        $client->request('GET', '/app/events/aa-aa-aa-aa-aa/edit');

        $this->assertResponseStatusCodeSame(404);
    }

    public function testWithoutAuthenticatedUser(): void
    {
        $client = static::createClient();
        $client->request('GET', '/app/events/f1f992d3-3cf5-4eb2-9b83-f112b7234613/edit');
        $this->assertResponseRedirects('http://localhost/login', 302);
    }
}
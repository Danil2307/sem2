<?php

namespace App\Tests\Client;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ClientTest extends WebTestCase
{
    private array $trueData = ['email' => 'maUser@mail.ru', 'password' => 'mamama'];
    private array $falseData = ['email' => 'maUser@mail.ru', 'password' => '12345678'];

    public function testIndexPage(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
        $this->assertPageTitleContains('QA');
        $this->assertCount(15, $crawler->filter('.question'));

        $link = $crawler->filter('.question')->link();
        $client->click($link);
        $this->assertResponseStatusCodeSame(200);
        $this->assertPageTitleContains('Вопрос');
    }

    public function testAuthentication(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');
        $link = $crawler->filter('#btn-authorization')->link();
        $client->click($link);
        $this->assertResponseStatusCodeSame(200);
        $this->assertPageTitleContains('Вход');
        $client->submitForm('Войти', $this->falseData);
        $this->assertResponseRedirects();
        $client->followRedirect();
        $this->assertSelectorTextContains('.alert-danger', 'Неверный email или пароль');
        $client->submitForm('Войти', $this->trueData);
        $this->assertResponseRedirects();
        $client->followRedirect();
        $this->assertPageTitleContains('QA');
    }

    public function testPublicQuestion()
    {
        $client = static::createClient();


        $crawler = $client->request('GET', '/publicQuestion');
        $this->assertResponseRedirects();
        $client->followRedirect();

        $this->assertPageTitleContains('Вход');
        $client->submitForm('Войти', $this->trueData);

        $this->assertResponseRedirects();
        $crawler = $client->followRedirect();
        $this->assertPageTitleContains('QA');

        $link = $crawler->filter('#btn-publicQuestion')->link();
        $client->click($link);
//        $this->assertResponseRedirects();
//        $client->followRedirect();
//        $this->assertPageTitleContains('AestheticNews');
//        $client->submitForm('Войти', $this->trueData);
//        $this->assertResponseRedirects();
//        $client->followRedirect();
//        $this->assertPageTitleContains('Добавление новости');
        $questionEmpty = [
            'questions_form[title]' => ' ',
            'questions_form[text]' => 'Windows в последнее время сильно тормозит, подскажите что можно сделать? Может какие-либо вирусы почистить',
            'questions_form[category]' => 'Windows',
        ];
        $client->submitForm('Добавить вопрос', $questionEmpty);
        $this->assertSelectorTextContains('.alert-danger', 'Пожалуйста введите заголовок вопроса');
        $question = [
            'questions_form[title]' => 'Windows глючит',
            'questions_form[text]' => 'Windows в последнее время сильно тормозит, подскажите что можно сделать? Может какие-либо вирусы почистить',
            'questions_form[category]' => 'Windows',
        ];
        $client->submitForm('Добавить вопрос', $question);
        $this->assertResponseRedirects();
        $client->followRedirect();
        $this->assertPageTitleContains('QA');
    }


}

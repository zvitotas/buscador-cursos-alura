<?php

namespace Alura\BuscadorDeCursos;

use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;

class Buscador
{

    private $httpClient;
    private $crawler;
    public function __construct(ClientInterface $httpClient, Crawler $crawler)
    {
        $this->httpClient = $httpClient;
        $this->crawler = $crawler;
    }
    public function buscar(string $url): array
    {
        $resposta = $this->httpClient->request('GET', 'https://alura.com.br/cursos-online-programacao/php');

        $html = $resposta->getBody();

        $crawler = new Crawler();
        $crawler->addHtmlContent($html);

        $ElementosCursos = $crawler->filter('span.card-curso__nome');
        $cursos = [];

        foreach ($ElementosCursos as $elemento) {
           $cursos[] = $elemento->textContent;
        }

        return $cursos;
    }
}
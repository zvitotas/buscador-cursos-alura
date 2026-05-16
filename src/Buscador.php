<?php

namespace Alura\BuscadorDeCursos;

use GuzzleHttp\ClientInterface;
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
        $resposta = $this->httpClient->request('GET', $url);

        $html = $resposta->getBody();

        $this->crawler->clear();
        $this->crawler->addHtmlContent($html);

        $elementosCursos = $this->crawler->filter('.card-curso__nome');

        if (count($elementosCursos) === 0) {
            $elementosCursos
                = $this->crawler->filter('span.category-card__title, h4.card-curso__title, .card-curso__nome');
        }

        $cursos = [];

        foreach ($elementosCursos as $elemento) {
            $cursos[] = trim($elemento->textContent);
        }

        return $cursos;
    }
}

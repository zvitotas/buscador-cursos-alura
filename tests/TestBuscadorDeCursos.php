<?php

namespace tests;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\StreamInterface;
use Symfony\Component\DomCrawler\Crawler;
use Alura\BuscadorDeCursos\Buscador;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\ClientInterface;


class TestBuscadorDeCursos extends TestCase
{
   private $httpClientMock;
   private $url = 'url-teste';

   protected function setUp(): void{}

    public function testBuscadorDeveRetornarCursos()
    {
        $html = '
        <span class="card-curso__nome">Curso Teste 1</span>
        <span class="card-curso__nome">Curso Teste 2</span>
        <span class="card-curso__nome">Curso Teste 3</span>
    ';

        $streamMock = $this->createMock(StreamInterface::class);

        $streamMock
            ->method('__toString')
            ->willReturn($html);

        $responseMock = $this->createMock(ResponseInterface::class);

        $responseMock
            ->method('getBody')
            ->willReturn($streamMock);

        $this->httpClientMock = $this->createMock(ClientInterface::class);

        $this->httpClientMock
            ->method('request')
            ->willReturn($responseMock);

        $crawler = new Crawler();

        $buscador = new Buscador($this->httpClientMock, $crawler);

        $cursos = $buscador->buscar($this->url);

        $this->assertCount(3, $cursos);
        $this->assertEquals('Curso Teste 1', $cursos[0]);
        $this->assertEquals('Curso Teste 2', $cursos[1]);
        $this->assertEquals('Curso Teste 3', $cursos[2]);
    }

};
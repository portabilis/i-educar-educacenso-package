<?php

namespace iEducar\Packages\Educacenso\Tests\Services;

use Generator;
use iEducar\Packages\Educacenso\Services\SplitFileService;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class SplitFileServiceTest extends TestCase
{
    /**
     * Instancia o service que faz o split do arquivo
     *
     *
     * @return SplitFileService
     */
    public function getService($fileName)
    {
        $file = new UploadedFile(__DIR__ . '/../assets/' . $fileName, $fileName);

        return new SplitFileService($file);
    }

    /**
     * O método getSplitedSchools deve retornar um Generator com as escolas contidas no arquivo
     */
    public function testNumberOfScools(): void
    {
        $service = $this->getService('oneschool');
        $result = $service->getSplitedSchools();
        $size = $this->countGenerator($result);

        $this->assertEquals(1, $size);

        $service = $this->getService('threeschools');
        $result = $service->getSplitedSchools();
        $size = $this->countGenerator($result);

        $this->assertEquals(3, $size);
    }

    /**
     * Se um arquivo vazio for passado, deverá retornar um Generator vazio
     */
    public function testEmptyFileShouldRetursEmpty(): void
    {
        $service = new SplitFileService(UploadedFile::fake()->create('fakefile'));
        $result = $service->getSplitedSchools();
        $size = $this->countGenerator($result);

        $this->assertEquals(0, $size);
    }

    /**
     * Retorna o tamanho de um generator
     *
     *
     * @return int
     */
    private function countGenerator(Generator $generator)
    {
        $count = 0;
        foreach ($generator as $key => $value) {
            $count++;
        }

        return $count;
    }
}

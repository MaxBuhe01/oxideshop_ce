<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\EshopCommunity\Tests\Unit\Internal\Twig\Loader;

use OxidEsales\EshopCommunity\Internal\Templating\TemplateLoaderInterface;
use OxidEsales\EshopCommunity\Internal\Twig\Loader\FilesystemLoader;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Class FilesystemLoaderTest
 *
 * @author Tomasz Kowalewski (t.kowalewski@createit.pl)
 */
class FilesystemLoaderTest extends TestCase
{

    public function testEmptyConstructor()
    {
        $loader = new FilesystemLoader();
        $this->assertEquals([], $loader->getPaths());
    }

    /**
     * @expectedException Twig\Error\LoaderError
     */
    public function testFindTemplateLoadError()
    {
        /** @var TemplateLoaderInterface|MockObject $internalLoader */
        $internalLoader = $this->createMock(TemplateLoaderInterface::class);
        $internalLoader->method('getPath')->willReturn("");

        $loader = new FilesystemLoader([], null, $internalLoader, $internalLoader);

        $loader->getSourceContext('foo')->getCode();
    }

    public function testFindTemplateByParentClass()
    {
        $basePath = __DIR__ . "/Fixtures";

        $loader = new FilesystemLoader([$basePath]);

        $this->assertEquals('index file', $loader->getSourceContext('index.html.twig')->getCode());
    }

    public function testFindTemplateByInternalLoader()
    {
        /** @var TemplateLoaderInterface|MockObject $internalLoader */
        $internalLoader = $this->createMock(TemplateLoaderInterface::class);
        $internalLoader->method('getPath')->willReturnArgument(0);

        $loader = new FilesystemLoader([], null, $internalLoader, $internalLoader);

        $templateName = 'internal_index.html.twig';
        $this->assertEquals($templateName, $loader->findTemplate($templateName));
    }
}

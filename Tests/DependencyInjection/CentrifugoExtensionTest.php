<?php

namespace Kismia\CentrifugoBundle\Tests\DependencyInjection;

use Kismia\CentrifugoBundle\DependencyInjection\CentrifugoExtension;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Yaml\Yaml;


class CentrifugoExtensionTest extends TestCase
{

    public function testLoad()
    {

        $config = Yaml::parseFile(__DIR__.'/../Fixtures/config.yaml');


        $container = $this->getContainer($config);
        $this->assertTrue($container->hasDefinition('centrifugo.client'));

        $defination = $container->getDefinition('centrifugo.client');
        $arguments = $defination->getArguments();


        $this->assertEquals('%centrifugo.apiendpoint%', $arguments[0]);
        $this->assertEquals('%centrifugo.secret%', $arguments[1]);
        $this->assertEquals('%centrifugo.transport%', $arguments[2]);

    }


    protected function getContainer(array $config = array(), array $thirdPartyDefinitions = array())
    {
        $container = new ContainerBuilder();
        foreach ($thirdPartyDefinitions as $id => $definition) {
            $container->setDefinition($id, $definition);
        }
        $container->getCompilerPassConfig()->setOptimizationPasses([]);
        $container->getCompilerPassConfig()->setRemovingPasses([]);

        $loader = new CentrifugoExtension();
        $loader->load($config, $container);
        $container->compile();

        return $container;
    }
}


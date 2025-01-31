<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\UX\Chartjs\Tests;

use PHPUnit\Framework\TestCase;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;
use Symfony\UX\Chartjs\Tests\Kernel\TwigAppKernel;

/**
 * @author Titouan Galopin <galopintitouan@gmail.com>
 *
 * @internal
 */
class ChartExtensionTest extends TestCase
{
    public function testRenderChart()
    {
        $kernel = new TwigAppKernel('test', true);
        $kernel->boot();
        $container = $kernel->getContainer()->get('test.service_container');

        /** @var ChartBuilderInterface $builder */
        $builder = $container->get('test.chartjs.builder');

        $chart = $builder->createChart(Chart::TYPE_LINE);

        $chart->setData([
            'labels' => ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
            'datasets' => [
                [
                    'label' => 'My First dataset',
                    'backgroundColor' => 'rgb(255, 99, 132)',
                    'borderColor' => 'rgb(255, 99, 132)',
                    'data' => [0, 10, 5, 2, 20, 30, 45],
                ],
            ],
        ]);

        $chart->setOptions([
            'showLines' => false,
        ]);

        $rendered = $container->get('test.chartjs.twig_extension')->renderChart(
            $chart,
            ['data-controller' => 'mycontroller', 'class' => 'myclass']
        );

        $this->assertSame(
            '<canvas data-controller="mycontroller symfony--ux-chartjs--chart" data-symfony--ux-chartjs--chart-view-value="{&quot;type&quot;:&quot;line&quot;,&quot;data&quot;:{&quot;labels&quot;:[&quot;January&quot;,&quot;February&quot;,&quot;March&quot;,&quot;April&quot;,&quot;May&quot;,&quot;June&quot;,&quot;July&quot;],&quot;datasets&quot;:[{&quot;label&quot;:&quot;My First dataset&quot;,&quot;backgroundColor&quot;:&quot;rgb(255, 99, 132)&quot;,&quot;borderColor&quot;:&quot;rgb(255, 99, 132)&quot;,&quot;data&quot;:[0,10,5,2,20,30,45]}]},&quot;options&quot;:{&quot;showLines&quot;:false}}" class="myclass"></canvas>',
            $rendered
        );
    }
}

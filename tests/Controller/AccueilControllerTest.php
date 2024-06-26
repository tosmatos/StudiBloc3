<?php 

namespace Test\Controller;

use App\Controller\AccueilController;
use App\Entity\Visitor;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class AccueilControllerTest extends KernelTestCase
{
    public function testAccueilVisitorCount(): void
    {
        $visitor = new Visitor();
        $visitor->setCounter(10);

        $accueilController = new AccueilController();

        $accueilController->incrementVisitors($visitor);

        $this->assertEquals(11, $visitor->getCounter());
    }
} 
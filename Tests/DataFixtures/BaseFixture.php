<?php
namespace Dende\CalendarBundle\Tests\DataFixtures;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Yaml\Yaml;

/**
 * Class BaseFixture.
 */
abstract class BaseFixture extends AbstractFixture implements OrderedFixtureInterface
{
    protected $manager;
    protected $fixtureFile;

    public function load(ObjectManager $manager)
    {
        $this->manager = $manager;

        $file = $this->translateClassToFilename($this);

        $class_info = new \ReflectionClass($this);
        $dir = dirname($class_info->getFileName());

        $value = Yaml::parse(file_get_contents($dir . "/../Yaml/" . $file));

        foreach ($value as $key => $params) {
            $object = $this->insert($params);
            $this->addReference($key, $object);
            $this->manager->persist($object);
        }

        $this->manager->flush();
    }

    public function getOrder()
    {
        return 1;
    }

    public function translateClassToFilename($object)
    {
        $classnameArray = explode("\\", get_class($object));
        $class          = array_pop($classnameArray);
        $filename       = strtolower(substr($class, 0, strpos($class, 'Data'))).'.yml';

        return $filename;
    }

    protected function getArrayOfReferences(array $array)
    {
        $result = [];
        foreach ($array as $reference) {
            $result[] = $this->getReference($reference);
        }

        return $result;
    }
}

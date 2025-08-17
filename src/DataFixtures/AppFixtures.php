<?php
namespace App\DataFixtures;

use App\Entity\Planete;
use App\Entity\Satellite;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $names = ['Mercure', 'Vénus', 'Terre', 'Mars', 'Jupiter', 'Saturne', 'Uranus', 'Neptune'];
        $planetes = [];
        
        foreach ($names as $name) {
            $planet = new Planete();
            $planet->setName($name);
            $planet->setSurface(random_int(10, 200));
            $manager->persist($planet);
            $planetes[$name] = $planet;
        }
        
        $moon = new Satellite();
        $moon->setName('Lune');
        $moon->setDiameter(3474);
        $moon->setPlanete($planetes['Terre']);
        $manager->persist($moon);
        
        $phobos = new Satellite();
        $phobos->setName('Phobos');
        $phobos->setDiameter(22);
        $phobos->setPlanete($planetes['Mars']);
        $manager->persist($phobos);
        
        $deimos = new Satellite();
        $deimos->setName('Deimos');
        $deimos->setDiameter(12);
        $deimos->setPlanete($planetes['Mars']);
        $manager->persist($deimos);
        
        $manager->flush();
    }}
<?php

namespace App\Command;

use App\Entity\Day;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

// To run this command: php bin/console app:fill-days

class FillDaysCommand extends Command
{
    protected static $defaultName = 'app:fill-days';

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;

        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $startDate = new \DateTime('2023-07-03');
        $endDate = new \DateTime('2023-10-01');

        for ($date = clone $startDate; $date <= $endDate; $date->modify('+1 day')) {
            $day = new Day();
            $day->setDate(clone $date);

            // Set points according to the day of the week
            switch ($date->format('N')) {
                case 1: case 2: case 3: // Monday, Tuesday, Wednesday
                $day->setType('weekday');
                $day->setPoints(2);
                break;
                case 4: // Thursday
                    $day->setType('weekday');
                    $day->setPoints(1);
                    break;
                case 5: // Friday
                    $day->setType('weekday');
                    $day->setPoints(3);
                    break;
                case 6: // Saturday
                    $day->setType('weekend');
                    $day->setPoints(5);
                    break;
                case 7: // Sunday
                    $day->setType('weekend');
                    $day->setPoints(4);
                    break;
            }

            // Check for public holidays
            if ($date->format('m-d') == '07-21' || $date->format('m-d') == '08-15') {
                $day->setType('holiday');
                $day->setPoints(5);
            }

            $this->entityManager->persist($day);
        }

        $this->entityManager->flush();

        $output->writeln('Days filled successfully.');

        return Command::SUCCESS;
    }
}


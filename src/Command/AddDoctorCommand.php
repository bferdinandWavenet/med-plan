<?php

namespace App\Command;

use App\Entity\Doctor;
use App\Entity\Day;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

// To run this command: php bin/console app:add-doctor

class AddDoctorCommand extends Command
{
    protected static $defaultName = 'app:add-doctor';

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;

        parent::__construct();
    }

    protected function configure()
    {
        $this->setDescription('Adds a new doctor.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $helper = $this->getHelper('question');

        $question = new Question('Please enter the name of the doctor: ', 'Doctor Name');
        $name = $helper->ask($input, $output, $question);

        $doctor = new Doctor();
        $doctor->setName($name);

        $question = new Question('Please enter the IDs of the unavailable days, separated by comma (or leave blank for none): ', '');
        $unavailableDaysIds = $helper->ask($input, $output, $question);

        if (!empty($unavailableDaysIds)) {
            $unavailableDaysIds = explode(',', $unavailableDaysIds);
            foreach ($unavailableDaysIds as $dayId) {
                $day = $this->entityManager->getRepository(Day::class)->find(trim($dayId));
                if ($day) {
                    $doctor->setUnavailableDays($day);
                }
            }
        }

        $this->entityManager->persist($doctor);
        $this->entityManager->flush();

        $output->writeln('Doctor added successfully.');

        return Command::SUCCESS;
    }
}

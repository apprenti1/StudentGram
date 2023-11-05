<?php
// src/Command/UserValidateCommand.php
namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;

class UserValidateCommand extends Command
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();

        $this->entityManager = $entityManager;
    }

    protected function configure()
    {
        $this
            ->setName('app:user:validate')
            ->setDescription('Validate a user account')
            ->addArgument('user-id', InputArgument::REQUIRED, 'User ID to validate')
            ->addOption('validate', null, InputOption::VALUE_NONE, 'Validate the user account');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $userId = $input->getArgument('user-id');
        $validate = $input->getOption('validate');

        $userRepository = $this->entityManager->getRepository(User::class);
        $user = $userRepository->find($userId);

        if ($user) {
            // Set the "validated" field to true
            if ($validate) {
                $user->setValidated(true);
            }else{
                $user->setValidated(false);
            }
            // Add additional roles if necessary
            $user->setRoles(['ROLE_USER', 'ROLE_VALIDATED']); // Example roles

            $this->entityManager->flush();

            $output->writeln('User account has been validated.');
        } else {
            $output->writeln('User not found.');
        }

        return Command::SUCCESS;
    }
}

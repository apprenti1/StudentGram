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

class SetSuperadmin extends Command
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
            ->setName('set:superadmin')
            ->setDescription('Mettre un utilisateur en superadmin')
            ->addArgument('email', InputArgument::REQUIRED, 'Email utilisateur');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $email = $input->getArgument('email');

        $userRepository = $this->entityManager->getRepository(User::class);
        $user = $userRepository->findOneBy(['email'=> $email]);
        $roles = $user->getRoles();

        if ($user) {
            if (in_array("ROLE_SUPERADMIN", $roles)) {
                $user->setRoles(array_diff($roles, ["ROLE_SUPERADMIN"]));
                $output->writeln('User '.$email.$user->getEmail().' no longer has the superadmin role');

            }else{
                $roles = array_diff($roles, ["ROLE_ADMIN"]);
                $user->setRoles(array_merge($roles, ["ROLE_SUPERADMIN"]));
                $output->writeln('User '.$email.' now has the superadmin role');
            }
            
            $this->entityManager->persist($user);
            $this->entityManager->flush();


        } else {
            $output->writeln('User '.$email.' not found.');
        }

        return Command::SUCCESS;
    }
}

<?php

namespace App\Command;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Utils\CustomValidatorForCommand;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\RuntimeException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\String\Slugger\AsciiSlugger;

class CreateUserCommand extends Command
{
    protected static $defaultName = 'create-user';

    private $validator;

    private SymfonyStyle $io;

    private $entityManager;

    private $userRepository;

    private $passwordHasher;
    
    public function __construct(CustomValidatorForCommand $validator, EntityManagerInterface $entityManager, UserRepository $userRepository, UserPasswordHasherInterface $passwordHasher)
    {
        parent::__construct();

        $this->validator = $validator;
        $this->entityManager = $entityManager;
        $this->userRepository = $userRepository;
        $this->passwordHasher = $passwordHasher;

    }

    protected function configure(): void
    {
        $this
            ->setDescription('Créé un utilisateur en base de données.')
            ->addArgument('email', InputArgument::REQUIRED, 'User email')
            ->addArgument('plainPassword', InputArgument::REQUIRED, 'User password')
            ->addArgument('roles', InputArgument::REQUIRED, 'User role')
            ->addArgument('gamerTag', InputArgument::REQUIRED, 'User gamerTag');
    }

    /**
    * Executed after configure() to initialize based on this input arguments and options
    *
    * @param InputInterface $input
    * @param OutputInterface $output
    * @return void
    */
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->io = new SymfonyStyle($input, $output);
    }

    /**
    * Executed after initialize() and before execute()
    * Checks if some of the options or arguments are missing and ask the user for those values.
    *
    * @param InputInterface $input
    * @param OutputInterface $output
    * @return void
    */
    protected function interact(InputInterface $input, OutputInterface $output)
    {
        $this->io->section("Ajout d'un utilisateur en BDD");

        $this->enterEmail($input, $output);

        $this->enterPassword($input, $output);

        $this->enterRoles($input, $output);

        $this->enterGamerTag($input, $output);

    }

    /**
     * Undocumented function
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return integer
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        /**
         * @var string $email
         */
        $email = $input->getArgument('email');

        /**
         * @var string $plainPassword
         */
        $plainPassword = $input->getArgument('plainPassword');

        /**
         * @var array<string> $role
         */
        $roles = [$input->getArgument('roles')];

        /**
         * @var string $gamerTag
         */
        $gamerTag = $input->getArgument('gamerTag');
        
        $user = new User();

        $user->setEmail($email)
             ->setPassword($this->passwordHasher->hashPassword($user, $plainPassword))
             ->setRoles($roles)
             ->setGamerTag($gamerTag);
        
        $slugger = new AsciiSlugger();
        $createSlug = $slugger->slug($user->getGamerTag());
        $createSlug = strtolower($createSlug);
        $user->setSlug($createSlug);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $this->io->success("Un nouvel utilisateur est present en BDD");

        return Command::SUCCESS;
    }

    /**
     * Sets the User Email
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */
    private function enterEmail(InputInterface $input, OutputInterface $output)
    {
        $helper = $this->getHelper('question');

        $emailQuestion = new Question("Email de l'utilisateur :");

        $emailQuestion->setValidator([$this->validator, 'validateEmail']);

        $email = $helper->ask($input, $output, $emailQuestion);

        if($this->isUserAlreadyExists($email))
        {
            throw new RuntimeException(sprintf("Utilisateur deja present en BDD"));
        }

        $input->setArgument('email', $email);
    }

    /**
     * Checs if an user already exists in databasewith the email entered by the user in the CLI
     *
     * @param string $email
     * @return User|null
     */
    private function isUserAlreadyExists(string $email): ?User
    {
        return $this->userRepository->findOneBy([
            'email' => $email
        ]);
    }

    /**
     * Sets the User Password
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */
    private function enterPassword(InputInterface $input, OutputInterface $output)
    {
        $helper = $this->getHelper('question');

        $passwordQuestion = new Question("Mot de passe de l'utilisateur :");

        $passwordQuestion->setValidator([$this->validator, 'validatePassword']);

        $passwordQuestion->setHidden(true)->setHiddenFallback(false);

        $password = $helper->ask($input, $output, $passwordQuestion);

        $input->setArgument('plainPassword', $password);
    }

    /**
     * Sets the User Role
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */
    private function enterRoles(InputInterface $input, OutputInterface $output)
    {
        $helper = $this->getHelper('question');

        $roleQuestion = new ChoiceQuestion(
            "Role de l'utilisateur :", 
            ['ROLE_USER', 'ROLE_ADMIN'],
            'ROLE_USER'
        );

        $roleQuestion->setErrorMessage('Role utilisateur invalide.');

        $roles = $helper->ask($input, $output, $roleQuestion);

        $output->writeln("<info>Role utilisateur pris en compte : {$roles}</info>");

        $input->setArgument('roles', $roles);
    }

    /**
     * Sets the User GamerTag
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */
    private function enterGamerTag(InputInterface $input, OutputInterface $output)
    {
        $helper = $this->getHelper('question');

        $gamerTagQuestion = new Question("GamerTag de l'utilisateur :");

        $gamerTag = $helper->ask($input, $output, $gamerTagQuestion);

        $input->setArgument('gamerTag', $gamerTag);
    }

}

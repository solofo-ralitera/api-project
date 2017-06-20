<?php

namespace ApiBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ApiInitCommand extends ContainerAwareCommand
{
    protected function configure() {
        $this
            ->setName('api:init')
            ->setDescription('Init api data')
            ->setHelp('This command allows you to init minimum data for the beginning')
            ->addArgument('fakeuser', InputArgument::OPTIONAL, 'Number of fake user to be created', 10)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $this->getContainer()->get('api.service')->init([
            'fakeuser' => (int) $input->getArgument('fakeuser'),
            'userManager' => $this->getContainer()->get('fos_user.user_manager')
        ]);
    }
}
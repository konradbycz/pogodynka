<?php

namespace App\Command;

use App\Service\WeatherService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'weather:getByCountryCity',
    description: 'Add a short description for your command',
)]
class WeatherGetByCountryCityCommand extends Command
{
    private WeatherService $weatherService;

    public function __construct(WeatherService $weatherService, string $name = null)
    {
        $this->weatherService = $weatherService;
        parent::__construct($name);
    }


    protected function configure(): void
    {
        $this
            ->addArgument('country', InputArgument::REQUIRED)
            ->addArgument('city', InputArgument::REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $country = $input->getArgument('country');
        $city = $input->getArgument('city');

        $measurements = $this->weatherService->getWeatherForCountryAndCity($country, $city);

        foreach($measurements as $measurement){
            $io->success($measurement->getLocation().' '.$measurement->getDate()->format('d.m.Y').' '.$measurement->getCelsius());
        }

        return Command::SUCCESS;
    }
}

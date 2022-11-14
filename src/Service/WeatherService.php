<?php

namespace App\Service;

use App\Repository\LocationRepository;
use App\Repository\MeasurementRepository;

class WeatherService
{
    private LocationRepository $locationRepository;
    private MeasurementRepository $measurementRepository;

    public function __construct(LocationRepository $locationRepository, MeasurementRepository $measurementRepository)
    {
        $this->locationRepository = $locationRepository;
        $this->measurementRepository = $measurementRepository;
    }

    public function getWeatherForLocation($location){
        return $this->measurementRepository->findByLocation($location);
    }

    public function getWeatherForCountryAndCity($countryCode, $cityName){
        $location = $this->locationRepository->findByCountryAndCity($countryCode, $cityName);
        return $this->getWeatherForLocation($location);
    }

    public function getWeatherById($locationId){
        $location = $this->locationRepository->findById($locationId);
        return $this->getWeatherForLocation($location);
    }
}
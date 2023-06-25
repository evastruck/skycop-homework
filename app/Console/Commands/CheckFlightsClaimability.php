<?php

namespace App\Console\Commands;

use App\Services\CsvService;
use App\Services\FlightClaimabilityService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class CheckFlightsClaimability extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-flights-claimability';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check flights are claimable or not';

    /**
     * Path to CSV file.
     */
    private const CSV_PATH = 'app/flights.csv';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->validate();
        $data = (new CsvService(storage_path(self::CSV_PATH)))->getData();

        if (empty($data)) {
            $this->error('No data provided.');
            exit;
        }

        $flightClaimabilityService = new FlightClaimabilityService;

        foreach ($data as $flight) {
            $claimable = $flightClaimabilityService->isFlightClaimable($flight) ? 'Y' : 'N';
            printf("%s %s %s %s\n", $flight['Countries'], $flight['Status'], $flight['Status Details'], $claimable);
        }

        $this->info('Handled successfully!');
    }

    /**
     * Validate file.
     *
     * @return void
     */
    private function validate(): void
    {
        if (!Storage::exists(Storage::disk('local')->exists(self::CSV_PATH))) {
            $this->error('CSV file does not exist.');
            exit;
        }
    }
}

<?php

use Flynsarmy\CsvSeeder\CsvSeeder;
use Illuminate\Support\Facades\DB;

class ProjectsTableSeeder extends CsvSeeder
{
	public function __construct()
	{
		$this->table = 'projects';
		$this->filename = base_path().'/database/seeds/csvs/sample_projects_data.csv';
	}

    public function run()
    {
		// Recommended when importing larger CSVs
		DB::disableQueryLog();

		// Uncomment the below to wipe the table clean before populating
		DB::table($this->table)->truncate();

		parent::run();
    }
}

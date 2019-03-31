<?php
namespace loft;

class Api
{

	/**
	 * List of delivery notes
	 * @var mixed
	 */
	private $notes;

	/**
	 * List of all starting locations
	 * @var array
	 */
	private $startLocations = [];

	/**
	 * List of all ending locations
	 * @var array
	 */
	private $endLocations = [];

	/**
	 * List of routes in correct order
	 * @var
	 */
	private $routes;


	/**
	 * Api constructor.
	 * Set necessary variables
	 * and initialize sorting process
	 * @param $json
	 */
	public function __construct($json)
	{
		$this->notes = json_decode($json);

		$this->setLocations();
		$this->sortLocations();
		$this->output(true);
	}

	/**
	 * Sort Locations in order of it's routes [recursion]
	 * @param null $note
	 */
	private function sortLocations($note=null)
	{

		if($note===null) {
			$note = $this->notes[ key($this->getFirstLocation()) ];
			$this->routes[] = $note;
		} // Starting Location

		// If we still have some delivery notes to check/process
		if(is_object($note) and count($this->notes)>1)
		{
			$endLocation = $this->getEndLocation($note);
			$this->routes[] = $endLocation; // Put checkpoint into the list of routes
			$this->sortLocations($endLocation); // Find another Location step
		}

	}

	/**
	 * Get End Location of current note
	 * Remove note from the list of delivery notes
	 * @param $note
	 * @return mixed
	 */
	private function getEndLocation($note)
	{
		foreach ($this->notes as $keyNote => $testNote) {
			if ($note->endLocation == $testNote->startLocation) {
				unset($this->notes[$keyNote]); // Remove note from the list
				return $testNote; // Return note that is End Location
			}
		}
	}

	/**
	 * @return array
	 */
	private function getFirstLocation()
	{
		return array_diff($this->startLocations, $this->endLocations);
	}

	/**
	 * Generates list of all starting and ending Locations
	 */
	private function setLocations()
	{
		if(is_array($this->notes)) {
			foreach ($this->notes as $key => $note) {
				$this->startLocations[$key] = $note->startLocation;
				$this->endLocations[$key] = $note->endLocation;
			}
		}
	}

	/**
	 * Output Routes
	 * @param bool $json
	 */
	private function output($json=false)
	{
		if($json===true) echo json_encode($this->routes, JSON_UNESCAPED_UNICODE);
		else $this->show($this->routes);
	}

	/**
	 * @param $arr
	 */
	public function show($arr)
	{
		echo '<pre>';
		print_r($arr);
		echo '</pre>';
	}
}
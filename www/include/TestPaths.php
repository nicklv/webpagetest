<?php

/**
 * Takes the responsibilty for creating file names and paths for test-related files.
 * This way, the logic of file names and the file names itself get encapsulated and can get tested.
 */
class TestPaths {
  const UNDERSCORE_FILE_PATTERN = "/^(?P<run>[0-9]+)_(?P<cached>Cached_)?((?P<step>[0-9]+)_)?(?P<name>[\S]+)$/";

  protected $testRoot;

  protected $run;
  protected $cached;
  protected $step;

  protected $parsedBaseName;

  /**
   * TestPaths constructor.
   * @param string $testRoot The path where all data of the test is stored in
   * @param int $run Number of the run (>= 1)
   * @param bool $cached If this is a cached run or not
   * @param int $step The number of the step (>= 1)
   */
  public function __construct($testRoot = ".", $run = 1, $cached = false, $step = 1) {
    $this->testRoot = $this->formatTestRoot($testRoot);
    $this->run = intval($run);
    $this->cached = $cached ? true : false;
    $this->step = intval($step);
  }

  /**
   * Makes sure testRoot is either empty or stops with a slash "/"
   * @param $raw string The input testRoot
   * @return string The formatted testRoot
   */
  private function formatTestRoot($raw) {
    $testRoot = strval($raw);
    $length = strlen($testRoot);
    if ($length && $testRoot[$length - 1] != "/") {
      $testRoot .= "/";
    }
    return $testRoot;
  }

  /**
   * @param string $testRoot The path where all data of the test is stored in
   * @param $fileName string File name to instantiate the object from
   * @return TestPaths A new instance or NULL
   */
  public static function fromUnderscoreFileName($testRoot, $fileName) {
    if (!preg_match(self::UNDERSCORE_FILE_PATTERN, $fileName, $matches)) {
      return NULL;
    }
    $step = empty($matches["step"]) ? 1 : intval($matches["step"]);
    $instance = new self($testRoot, $matches["run"], !empty($matches["cached"]), $step);
    $instance->parsedBaseName = $matches["name"];
    return $instance;
  }

  /**
   * @return bool True if the paths are for a cached run (repeat view), false otherwise (first view)
   */
  public function isCachedResult() {
    // It's not very nice to have this function in this class, but this information is actually needed and contained here
    return $this->cached;
  }

  /**
   * @return string The base name of the file (without run information), e.g. when instantiated by from*Name methods
   */
  public function getParsedBaseName() {
    return $this->parsedBaseName;
  }

  /**
   * @return string Directory name to store video data in
   */
  public function videoDir() {
    return $this->testRoot . "video_" . strtolower($this->underscoreIdentifier());
  }

  /**
   * @return string Path for the page speed data file
   */
  public function pageSpeedFile() {
    return $this->testRoot . $this->underscoreIdentifier() . "_pagespeed.txt";
  }

  /**
   * @return string Path for headers file
   */
  public function headersFile() {
    return $this->testRoot . $this->underscoreIdentifier() . "_report.txt";
  }

  /**
   * @return string Path for bodies file
   */
  public function bodiesFile() {
    return $this->testRoot . $this->underscoreIdentifier() . "_bodies.zip";
  }

  /**
   * @return string Path for page data file
   */
  public function pageDataFile() {
    return $this->testRoot . $this->underscoreIdentifier() . "_IEWPG.txt";
  }

  /**
   * @return string Path for page data JSON file
   */
  public function pageDataJsonFile() {
    return $this->testRoot . $this->underscoreIdentifier() . "_page_data.json";
  }

  /**
   * @return string Path for request data file
   */
  public function requestDataFile() {
    return $this->testRoot . $this->underscoreIdentifier() . "_IEWTR.txt";
  }

  /**
   * @return string Path for utilization file
   */
  public function utilizationFile() {
    return $this->testRoot . $this->underscoreIdentifier() . "_progress.csv";
  }

  /**
   * @return string Path for JPG screenshot file
   */
  public function screenShotFile() {
    return $this->testRoot . $this->underscoreIdentifier() . "_screen.jpg";
  }

  /**
   * @return string Path for PNG screenshot file
   */
  public function screenShotPngFile() {
    return $this->testRoot . $this->underscoreIdentifier() . "_screen.png";
  }

  /**
   * @return string Path for status file
   */
  public function statusFile() {
    return $this->testRoot . $this->underscoreIdentifier() . "_status.txt";
  }

  /**
   * @return string Path for custom metrics file
   */
  public function customMetricsFile() {
    return $this->testRoot . $this->underscoreIdentifier() . "_metrics.json";
  }

  /**
   * @return string Path for user timed events
   */
  public function userTimedEventsFile() {
    return $this->testRoot . $this->underscoreIdentifier() . "_timed_events.json";
  }

  /**
   * @return string Path for Chrome trace user timing
   */
  public function chromeUserTimingFile() {
    return $this->testRoot . $this->underscoreIdentifier() . "_user_timing.json";
  }

  /**
   * @return string Path for devtools results
   */
  public function devtoolsFile() {
    return $this->testRoot . $this->underscoreIdentifier() . "_devtools.json";
  }

  /**
   * @return string Path for devtools CPU timeline
   */
  public function devtoolsCPUTimelineFile() {
    return $this->testRoot . $this->underscoreIdentifier() . "_timeline_cpu.json";
  }

  /**
   * @return string Path for devtools CPU timeline
   */
  public function devtoolsTraceFile() {
    return $this->testRoot . $this->underscoreIdentifier() . "_trace.json";
  }

  /**
   * @return string Path to the visual data cache file
   */
  public function visualDataCacheFile() {
    return $this->testRoot . $this->dotIdentifier() . ".visual.dat";
  }

  /**
   * @return string Path to the visual data file
   */
  public function visualDataFile() {
    return $this->testRoot . "llab_" . $this->dotIdentifier() . ".visual.dat";
  }

  /**
   * @return string Path to histograms file
   */
  public function histogramsFile() {
    return $this->testRoot . $this->dotIdentifier() . ".histograms.json";
  }

  /**
   * @param int $version The version of the cache format
   * @return string Path to cache file for devtools CPU times
   */
  public function devtoolsCPUTimeCacheFile($version) {
    return $this->testRoot . $this->dotIdentifier() . ".devToolsCPUTime." . $version;
  }

  /**
   * @param int $version The version of the cache format
   * @return string Path to cache file for devtools requests
   */
  public function devtoolsRequestsCacheFile($version) {
    return $this->testRoot . $this->dotIdentifier() . ".devToolsRequests." . $version;
  }

  protected function underscoreIdentifier() {
    return $this->run . ($this->cached ? "_Cached" : "") . ($this->step > 1 ? "_" . $this->step : "");
  }

  protected function dotIdentifier() {
    return $this->run . "." . ($this->cached ? "1" : "0") . ($this->step > 1 ? ("." . $this->step) : "");
  }

}


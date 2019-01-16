<?php

namespace Drupal\archivesspace;

use DateTime;
use InvalidArgumentException;

/**
 * Manages iteration of ArchivesSpace API search result sets.
 */
class ArchivesSpaceIterator implements \Countable, \Iterator {


  protected $session;
  protected $type;
  protected $types = [
    'repositories',
    'resources',
    'archival_objects',
    'digital_objects',
    'agents/people',
    'agents/corporate_entities',
    'agents/families',
    'subjects',
  ];
  protected $datetime;
  protected $repository;


  protected $count = -1;
  protected $loaded = [];
  protected $position = 0;
  protected $currentPage = 0;
  protected $lastPage;
  protected $offsetFirst = 0;
  protected $offsetLast = 0;
  /**
   * Default max set by ArchivesSpace is 250.
   *
   * @var int
   */
  protected $pageSize = 250;

  /**
   * {@inheritdoc}
   */
  public function __construct(string $type, DateTime $datetime, ArchivesSpaceSession $session, string $repository) {
    if (!in_array($type, $this->types)) {
      throw new InvalidArgumentException('Can\'t iterate over type: ' . $type);
    }
    $this->position = 0;
    $this->type = $type;
    $this->datetime = $datetime;
    $this->session = $session;
    $this->repository = $repository;
  }

  /**
   * {@inheritdoc}
   */
  public function rewind() {
    $this->position = 0;
    $this->loadPage(1);
  }

  /**
  * {@inheritdoc}
  */
  public function count() {
    $this->rewind();
    return $this->count;
  }

  /**
   * {@inheritdoc}
   */
  public function current() {
    return $this->loaded[$this->position];
  }

  /**
   * {@inheritdoc}
   */
  public function key() {
    return $this->position;
  }

  /**
   * {@inheritdoc}
   */
  public function next() {
    ++$this->position;
  }

  /**
   * {@inheritdoc}
   */
  public function valid() {

    if ($this->position < count($this->loaded)) {
      return TRUE;
    }

    // We may need to load more results.
    if ($this->currentPage < $this->lastPage) {
      $this->loadPage($this->currentPage + 1);
      // Now that we've loaded, check again.
      return $this->valid();
    }

    return FALSE;
  }

  /**
   * Loads a page of ArchivesSpace results.
   *
   * @param int $page
   *   An integer representing the page to load.
   */
  protected function loadPage($page) {
    // Do nothing if they try to go beyond the last known page.
    if (isset($this->lastPage) && $page > $this->lastPage) {
      return;
    }

    $parameters = [
      'page' => $page,
      'page_size' => $this->pageSize,
    ];

    // The API requires a repository for resources, archival objects, and digital_objects.
    $results  = $this->session->request('GET', $this->repository . '/' . $this->type, $parameters);

    // Repositories aren't paginated like everything else.
    if ($this->type == 'repositories'){
      $this->count    = count($results);
      $this->position = 0;
      $this->loaded   = $results;
    } else {
      $this->count       = $results['total'];
      $this->currentPage = $results['this_page'];
      $this->lastPage    = $results['last_page'];
      $this->position    = 0;
      $this->loaded      = $results['results'];
    }

  }

}

<?php

namespace Drupal\archivesspace;

use DateTime;
use InvalidArgumentException;

/**
 * Manages iteration of ArchivesSpace API search result sets.
 */
class ArchivesSpaceIterator implements \Iterator {


  protected $session;
  protected $type;
  protected $types = [
    'resource',
    'archival_object',
    'digital_object',
  ];
  protected $datetime;
  protected $repository;


  protected $count = 0;
  protected $loaded = [];
  protected $position = 0;
  protected $currentPage = 0;
  protected $lastPage;
  protected $offsetFirst = 0;
  protected $offsetLast = 0;
  protected $pageSize = 100;

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
  public function current() {
    // Loading happens with valid(), so current should be there.
    // 0-based positioning for loaded & position
    // 1-based positioning for offset
    // Loaded position is the difference between the
    // position's offset (pos+1) and the offsetFirst.
    $loaded_pos = $this->position + 1 - $this->offsetFirst;
    // Unfortunately/fortunately? The results bury the full json object
    // as a sub-field, rather than just giving it to us in the first place.
    // So, let's return that.
    // Oh, and the base SourcePlugin wants nested arrays.
    return json_decode($this->loaded[$loaded_pos]['json'], TRUE);
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
    // Offset is out of range?
    if (($this->position + 1) > $this->count) {
      return FALSE;
    }

    // Is position's offset loaded (comes after last loaded)?
    if (($this->position + 1) > $this->offsetLast) {
      // Get more!
      $this->loadPage($this->currentPage + 1);

      // What if the load failed for some reason?
      if (($this->position + 1) != $this->offsetFirst) {
        return FALSE;
      }
    }
    return TRUE;
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

    $aq = json_encode([
      'jsonmodel_type' => 'advanced_query',
      'query' => [
        'jsonmodel_type' => 'boolean_query',
        'op' => 'AND',
        'subqueries' => [
        [
          'field' => 'primary_type',
          'value' => $this->type,
          'comparator' => 'equals',
          'jsonmodel_type' => 'field_query',
        ],
        [
          'field' => 'user_mtime',
          'value' => $this->datetime->format('c'),
          'comparator' => 'greater_than',
          'jsonmodel_type' => 'date_field_query',
        ],
        ],
      ],
    ]);
    $parameters = [
      'page' => $page,
      'pageSize' => $this->pageSize,
      'aq' => $aq,
    ];

    // ROOT-level search requires a user to have ADMIN!
    // If we want a user with read-only, we have to limit search to
    // a repository where they have read-only permissions set.
    $results           = $this->session->request('GET', $this->repository . '/search', $parameters);
    $this->count       = $results['total_hits'];
    $this->currentPage = $results['this_page'];
    $this->lastPage    = $results['last_page'];
    $this->offsetFirst = $results['offset_first'];
    $this->offsetLast  = $results['offset_last'];
    $this->loaded      = $results['results'];

  }

}

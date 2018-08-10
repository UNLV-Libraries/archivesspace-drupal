<?php

namespace Drupal\archivesspace;

use Drupal\archivesspace\ArchivesSpaceSession;
use DateTime;
use InvalidArgumentException;

class ArchivesSpaceIterator implements \Iterator {


    // Session object
    protected $session;
    protected $type;
    protected $types = [
      'repository',
      'resource',
      'archival_object',
      'digital_object'
    ];
    protected $datetime;
    // Total count
    protected $count = 0;
    // loaded objects
    protected $loaded = [];
    // Current position
    protected $position = 0;
    // Current page
    protected $current_page = 0;
    protected $last_page;
    protected $offset_first = 0;
    protected $offset_last = 0;
    protected $page_size = 10;

    // public function __construct(string $type, DateTime $datetime, ArchivesSpaceSession $session = ArchivesSpaceSession()) {
    public function __construct(string $type, DateTime $datetime, ArchivesSpaceSession $session) {
        if(!in_array($type, $this->types)){
          throw new InvalidArgumentException('Can\'t iterate over type: '.$type);
        }
        $this->position = 0;
        $this->type = $type;
        $this->datetime = $datetime;
        $this->session = $session;
    }

    public function rewind() {
        $this->position = 0;
        $this->loadPage(1);
    }

    public function current() {
      // Loading happens with valid(), so current should be there.
      // 0-based positioning for loaded & position
      // 1-based positioning for offset
      // Loaded position is the difference between the
      // position's offset (pos+1) and the offset_first
      $loaded_pos = $this->position + 1 - $this->offset_first;
      // Unfortunately/fortunately? The results bury the full json object
      // as a sub-field, rather than just giving it to us in the first place.
      // So, let's return that.
      return json_decode($this->loaded[$loaded_pos]['json']);
    }

    public function key() {
        return $this->position;
    }

    public function next() {
        ++$this->position;
    }

    public function valid() {

        // offset is out of range?
        if( ($this->position + 1) > $this->count ){
          return false;
        }

        // Is position's offset loaded (comes after last loaded)?
        if( ($this->position + 1) > $this->offset_last ){
          $this->loadPage($current_page + 1); // Get more!

          // What if the load failed for some reason?
          if( ($this->position + 1) != $this->offset_first ){
            return false;
          }
        }
        return true;
    }

    protected function loadPage($page){
      // Do nothing if they try to go beyond the last known page.
      if (isset($this->last_page) && $page > $this->last_page){
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
              'jsonmodel_type' => 'field_query'
            ],
            [
              'field' => 'user_mtime',
              'value' => $this->datetime->format('c'),
              'comparator' => 'greater_than',
              'jsonmodel_type' => 'date_field_query'
            ]
          ]
        ],
      ]);
      $parameters = [
        'page' => $page,
        'page_size' => $this->page_size,
        'aq' => $aq
      ];
      // ROOT-level search requires a user to have ADMIN!
      // If we want a user with read-only, we have to limit search to
      // a repository where they have read-only permissions set.
      $results = $this->session->request('GET','/search', $parameters);
      $this->count        = $results['total_hits'];
      $this->current_page = $results['this_page'];
      $this->last_page    = $results['last_page'];
      $this->offset_first = $results['offset_first'];
      $this->offset_last  = $results['offset_last'];
      $this->loaded       = $results['results'];
    }
}

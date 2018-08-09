<?php

namespace Drupal\archivesspace\Plugin\migrate\source;

use Drupal\migrate\Plugin\MigrationInterface;
use Drupal\migrate_plus\Plugin\migrate\source\Url;

/**
 * Source plugin for retrieving data from ArchivesSpace.
 *
 * @MigrateSource(
 *   id = "archivesspace"
 * )
 */
class ArchivesSpaceSource extends Url {

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, MigrationInterface $migration) {

    # Check for a base URI configuration
    $base_uri = \Drupal::state()->get('archivesspace.base_uri');
    if (!empty($configuration['base_uri']) && preg_match("@^https?://@", $configuration['base_uri'])){
        $base_uri = $configuration['base_uri'];
    }

    # Put a base URI on relative URLs
    # but only if we have a valid base URI
    if (preg_match("@^https?://@", $base_uri)){

      # Need a fresh set to work with.
      if (!is_array($configuration['urls'])) {
        $configuration['urls'] = [$configuration['urls']];
      }

      # Now update each if they need a base.
      foreach($configuration['urls'] as $pos => $url){
        if ( !preg_match("@^https?://@", $url) ) {
          $configuration['urls'][$pos] = $base_uri . $url;
        }
      }
    }

    parent::__construct($configuration, $plugin_id, $plugin_definition, $migration);

    $this->sourceUrls = $configuration['urls'];
  }


  /**
   * Returns the initialized data parser plugin.
   * We use the JSON parser.
   *
   * @return \Drupal\migrate_plus\DataParserPluginInterface
   *   The data parser plugin.
   */
  public function getDataParserPlugin() {
    if (!isset($this->dataParserPlugin)) {
      $this->dataParserPlugin = \Drupal::service('plugin.manager.migrate_plus.data_parser')->createInstance('json', $this->configuration);
    }
    return $this->dataParserPlugin;
  }

}

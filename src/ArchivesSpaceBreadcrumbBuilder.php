<?php

namespace Drupal\archivesspace;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Breadcrumb\Breadcrumb;
use Drupal\Core\Breadcrumb\BreadcrumbBuilderInterface;
use Drupal\Core\Routing\RouteMatchInterface;

/**
 * Provides a breadcrumb builder for finding aids.
 */
class ArchivesSpaceBreadcrumbBuilder implements BreadcrumbBuilderInterface {

  /**
   * {@inheritdoc}
   */
  public function applies(RouteMatchInterface $attributes) {
    $node = $attributes->getParameter('node');
    if (!empty($node) && !is_string($node)) {
      return in_array($node->bundle(), [
        'archival_object',
        'archival_resource',
        'repository',
      ]);
    }
  }

  /**
   * {@inheritdoc}
   */
  public function build(RouteMatchInterface $route_match) {
    $node = $route_match->getParameter('node');
    $breadcrumb = new Breadcrumb();
    $parents = ArchivesSpaceBreadcrumbBuilder::walkRelations($node);

    // Don't include the current item. @TODO make configurable.
    array_pop($parents);
    $breadcrumb->addCacheableDependency($node);

    // Add parents to the breadcrumb.
    foreach ($parents as $crumb) {
      $breadcrumb->addCacheableDependency($crumb);
      $breadcrumb->addLink($crumb->toLink());
    }
    $breadcrumb->addCacheContexts(['route']);
    return $breadcrumb;
  }

  /**
   * Walks up the finding-aid hierarchy.
   */
  protected static function walkRelations(EntityInterface $entity) {
    if ($entity->hasField('field_as_parent') &&
      !$entity->get('field_as_parent')->isEmpty()) {
      $crumbs = ArchivesSpaceBreadcrumbBuilder::walkRelations($entity->get('field_as_parent')->entity);
      $crumbs[] = $entity;
      return $crumbs;
    }
    return [$entity];
  }

}

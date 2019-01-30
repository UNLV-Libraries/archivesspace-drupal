## AS2D8: ArchivesSpace-to-Drupal 8 integration
AS2D8 is a series of Drupal modules for harvesting data from ArchviesSpace. AS2D8 extends the Drupal core Migrate API with plugins, provides sensible defaults for displaying ArchivesSpace resources in Drupal and all of the usual Drupal content and website management tools. AS2D8 closely tracks the [Islandora CLAW project](https://github.com/Islandora-CLAW/CLAW). When the next-generation of Islandora is stable, AS2D8 will offer an integration point for Islandora.

A few possible use cases include:
- Using Drupal Views to build glossary displays, user-configurable sorting,
- Optionally deploying with Search API with Apache Solr to provide a configurable and granular control over search results
- Building custom reports, logs, and user analytics
- Developing customized "Omeka-like" online exhibitions of archival material
- Researcher annotations, user-administered "favorites", save-for-later carts
- Managing research requests
- Joining archival records to geo-locations

Changes can be made in real time or asynchronously in batches during cron run. Once ArchivesSpace records are consumed by Drupal, they can be further enriched with NISO-defined metadata, published in a customizable discovery theme layer, and published in web service formats for consumption by other resources.

## Pre-history
The original ArchivesSpace/Drupal Integration project is a began in 2014 as a [Kress Foundation-funded](http://www.kressfoundation.org) suite of Drupal 7 modules to support cataloging and discovery at the [American Academy in Rome](http://dhc.aarome.org). The project extends several Drupal modules including [RESTClient](https://www.drupal.org/project/restclient), [Web Service Data](https://www.drupal.org/project/wsdata), and custom code to request ArchivesSpace JSON objects and pipe them into Drupal entities. These entities can then be formatted, manipulated, and indexed via traditional Drupal methods (Views, Solr, Elastic Search, etc.). The original project is monitored and updated for maintenance fixes only. No new features will be considered or added.

Instead, this project provides an entirely new suite of Drupal 8 modules that adhere to PSR coding standards and module development best practices in order to release this suite to both ArchivesSpace and Drupal developer communities.

## Roadmap & product pipeline
### Building the Drupal data model
The content types and vocabularies cover:
- Repositories
- Resources
- Archival Objects
- Subjects (with a Geographic Location-specific sub-type)
- Agents (except software)
- Digital Objects (in a sub-module)

### Migration plugins
This module provides migrations for all the supported content types and vocabularies.

By default, resources or archival objects that do not have the "publish" flag set are not migrated, nor do archival objects with an unpublished ancestor. However, once a resource or archival object is migrated it will remain in Drupal even if the flag is changed in ArchivesSpace unless manually removed.

### Authentication
To migrate content the module must be configured with a valid ArchivesSpace API point, username, and password. The migrations assume the API point of 'localhost:8089', 'admin' user, and 'admin' password unless configured. Visit the page /admin/configuration/archivesspace in your Drupal site to configure these settings.

### Templates
TBD

### Search
TBD

### Features
TBD

### CMI
TBD

## Documentation
TBD

## Quick Start Guide
Assuming the Drupal site and ArchivesSpace are installed on the same host:
- Ensure all the dependencies are installed.
- Save the module to your drupal site's modules directory.
- Enable the ArchivesSpace module. (E.g. `drush en -y archivesspace`)
- Run the migrations. _Note: most of the migrations will run relatively quickly, within a few minutes. However, the final migration, archival objects, can take a very long time to process for large sites (more than an hour)._

## Using AS2D8
TBD

## System Requirements
TBD

## Other Notes
TBD

## License
AS2D8 is licensed on the same terms as Drupal, under GPLv2 or later.

[About Drupal licensing](https://www.drupal.org/about/licensing)

The AS2d8 license also covers its related modules, hacks, plugins, and configuration management.

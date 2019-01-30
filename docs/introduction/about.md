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

## Benchmarks
#### [BentoSpace](http://demo.martinezdev.com/bento/search/)
An ArchivesSpace-CollectionSpace integration project developed by the [Litchfield Historical Society](http://litchfieldhistoricalsociety.org/)

#### [Project Blacklight](http://projectblacklight.org/)
Established discovery framework for digital collections.

##### [DKAN](https://getdkan.org/)
A discovery platform for government open data. The technical inspiration for the antecedent ArchivesSpace-to-Drupal 7 modules.

## Roadmap & product pipeline
### Buidling the Drupal data model
TBD

### Migration plugins
TBD

### Authentication
TBD

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
TBD

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

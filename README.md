![AS2D8 Logo]()
## AS2D8: ArchivesSpace-to-Drupal 8 integration
The [AS2D8](https://www.as2d8.com/) suite of modules supports pulling ArchivesSpace data to Drupal 8. It extends the Drupal core Migrate API with plugins, and provides sensible defaults for displaying ArchivesSpace resources in Drupal, provides all of the usual Drupal theming site administration tools. AS2D8 closely tracks the [Islandora CLAW project](https://github.com/Islandora-CLAW/CLAW). When the next-generation of Islandora is stable, AS2D8 will offer an integration point for Islandora. 

A few possible use cases include:
- Using Drupal Views to build glossary displays, user-configurable sorting, 
- Optionally deploying with Search API with Apache Solr to provide a configurable and granular control over search results 
- Building custom reports, logs, and user analytics
- Developing customized "Omeka-like" online exhibitions of archival material
- Researcher annotations, user-administered "favorites", save-for-later carts
- Managing research requests
- Joining archival records to geo-locations

Changes can be made in real time or asynchronously in batches during cron run. Once ArchivesSpace records are consumed by Drupal, they can be further enriched with NISO-defined metadata, published in a customizable discovery theme layer, and further syndicated for consumption by other resources. 

## Pre-history
This project began in 2014 as a [Kress Foundation-funded](http://www.kressfoundation.org) suite of Drupal 7 modules to support cataloging and discovery at the [American Academy in Rome](http://dhc.aarome.org). The project extends several Drupal modules including [RESTClient](https://www.drupal.org/project/restclient), [Web Service Data](https://www.drupal.org/project/wsdata), and custom code to request ArchivesSpace JSON objects and pipe them into Drupal entities. These entities can then be formatted, manipulated, and indexed via traditional Drupal methods (Views, Solr, Elastic Search, etc.).

This project is monitored and updated for maintenance fixes only. No new features will be considered or added. Instead, the Acacdemy, together with partner institutions, seeks to create an entirely new suite of Drupal 8 modules that adhere to PSR coding standards and module development best practices in order to release this suite to both ArchivesSpace and Drupal developer communities.

## Why not develop ArchivesSpace plugins instead?
TBD

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
TBD. Full AS2D8 documentation is available at http://docs.as2d8.com/

## Quick Start Guide
TBD

## Using AS2D8
TBD

## System Requirements
TBD

## Other Notes
TBD

## License
This project is licensed under the MIT open source license.

## About the Authors

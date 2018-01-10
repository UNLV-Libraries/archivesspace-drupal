## AS2D8
#### ArchivesSpace-to-Drupal 8 integration

![AS2D8 Logo]()

#### About this project
The [AS2D8](https://www.as2d8.com/) suite of modules supports pulling ArchivesSpace data to Drupal 8. It extends the Drupal core Migrate API with plugins, provides sensible defaults for displaying ArchivesSpace content in Drupal, and . Changes can be made in real time or asynchronously in batches during cron run. Once ArchivesSpace records are consumed by Drupal, they can be further enriched with NISO-defined metadata, displayed in a customizable discovery theme layer, and further syndicated for consumption by other resources. 

A few possible use cases include:
- Using Drupal Views to build glossary displays, user-configurable sorting, 
- Optionally deploying with Search API with Apache Solr to provide a configurable and granular control over search results 
- Building custom reports, logs, and user analytics
- Developing customized "Omeka-like" online exhibitions of archival material
- Researcher annotations, user-administered "favorites", save-for-later carts
- Managing research requests
- Joining archival records to geo-locations

---

#### Pre-history
This project began in 2014 as a [Kress Foundation-funded](http://www.kressfoundation.org) suite of Drupal 7 modules to support cataloging and discovery at the [American Academy in Rome](http://www.aarome.org). The project extends several Drupal modules including cURL HTTP (chr), Web Service Data (wsdata), and custom code to request ArchivesSpace JSON objects and pipe them into Drupal entities. These entities can then be formatted, manipulated, and indexed via traditional Drupal methods (Views, Solr, Elastic Search, etc.).

This project is monitored and updated for maintenance fixes only. No new features will be considered or added. Instead, the Acacdemy, together with partner institutions, seeks to create an entirely new suite of Drupal 8 modules that adhere to PSR coding standards and module development best practices in order to release this suite to both ArchivesSpace and Drupal developer communities 

---

#### Why not develop ArchivesSpace plugins?

#### Benchmarks
##### [BentoSpace] (http://demo.martinezdev.com/bento/search/)
An ArchivesSpace-CollectionSpace integration project developed by the Litchfield Historical Society that closely tracks many of the features Developed by the [Litchfield Historical Society] (http://litchfieldhistoricalsociety.org/)

##### [Project Blacklight] (http://projectblacklight.org/)
Established discovery framework for digital collections.

##### [DKAN] (https://getdkan.org/)
A discovery platform for government open data. The inspiration for the antecedent ArchivesSpace-to-Drupal 7 modules.

---
#### Roadmap (and a few questions)

#### Buidling the Drupal data model

#### Themes

## Documentation

Full AS2D8 documentation is available at http://docs.as2d8.com/

## Quick Start Guide

## Using AS2D8

## System Requirements

## Other Notes

## License

This project is licensed under the MIT open source license.

## About the Authors

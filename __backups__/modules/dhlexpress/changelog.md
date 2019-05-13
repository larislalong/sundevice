# Changelog
All notable changes to this module will be documented in this file.

## [2.3.0] - 2018-01-26
### Added

- New menu that lets merchants generate bulk label
- Allow free delivery by zone from a certain amount using real-time DHL prices
- Option to include archive doc copy in label files

### Changed

- New column in Manifest label list

### Fixed

- Fix cast on weights, dimensions and prices
- Delete spaces around account number when saving caused by copy-paste
- Fix bug when there's more than 99 quantities in a cart
- Fix error when email address is longer than 50 chars
- Fix special chars bug on free label generation
- Fix occasional download label error when module directory is not writable

## [2.2.0] - 2017-11-23
### Fixed

- Fix error on the tracking cron
- Fix bulk tracking when more than 10 shipments

### Added

- Add logging feature on tracking, quotation, label operations and new order creation

## [2.1.6] - 2017-11-02
### Fixed

- Missing require in tracking cron task

### Changed

- Cron URL giving for tracking update

## [2.1.5] - 2017-10-24
### Fixed

- Handle multiple display of DHL Orders
- Missing cast in SQL queries

## [2.1.4] - 2017-10-19
### Changed

- Store label format along with the label
- Update the way merchants download labels

### Fixed

- Typo for French language (DHL Pickup)
- Label format identifier when generating a label
